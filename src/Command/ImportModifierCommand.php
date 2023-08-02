<?php

namespace AfmLibre\Pathfinder\Command;

use AfmLibre\Pathfinder\Entity\Armor;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterArmor;
use AfmLibre\Pathfinder\Entity\CharacterWeapon;
use AfmLibre\Pathfinder\Entity\ClassT;
use AfmLibre\Pathfinder\Entity\Feat;
use AfmLibre\Pathfinder\Entity\Level;
use AfmLibre\Pathfinder\Entity\Modifier;
use AfmLibre\Pathfinder\Entity\Race;
use AfmLibre\Pathfinder\Entity\Skill;
use AfmLibre\Pathfinder\Entity\Weapon;
use AfmLibre\Pathfinder\Modifier\ModifierListingEnum;
use AfmLibre\Pathfinder\Repository\ModifierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'pathfinder:import-modifier',
    description: 'Add a short description for your command',
)]
class ImportModifierCommand extends Command
{
    private SymfonyStyle $io;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private ModifierRepository $modifierRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'modifier, fiona');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');
        if (!$name) {
            $helper = $this->getHelper('question');
            $question = new ChoiceQuestion('What do you want to import ?', ['modifiers', 'fiona']);

            $question->setErrorMessage('This choice %s is invalid.');

            $name = $helper->ask($input, $output, $question);
        }

        $output->writeln($name.' import...');

        match ($name) {
            'fiona' => $this->importFiona(),
            'default' => $this->import()
        };

        $this->modifierRepository->flush();

        return Command::SUCCESS;
    }

    private function treatement(array $data, string $className)
    {
        foreach ($data as $name => $values) {
            if (!$object = $this->entityManager->getRepository($className)->findOneByName($name)) {
                $this->io->error('Objet non trouvé '.$className.' '.$name);
                continue;
            }
            foreach ($values as $ability => $value) {

                $abilityEnum = ModifierListingEnum::findByName($ability);

                if (!$modifier = $this->modifierRepository->findOneByIdClassNameAndAbility(
                    $object->getId(),
                    $className,
                    $abilityEnum
                )) {
                    $modifier = new Modifier($object->getId(), $className);
                    $modifier->name = $object->name;
                    $this->modifierRepository->persist($modifier);
                }
                $modifier->value = $value;
                $modifier->ability = $abilityEnum;
            }
        }
    }

    private function import()
    {
        $races = [
            'Elfe' => [
                ModifierListingEnum::ABILITY_DEXTERITY->value => +2,
                ModifierListingEnum::ABILITY_INTELLIGENCE->value => +2,
                ModifierListingEnum::ABILITY_CONSTITUTION->value => -2,
            ],
            'Halfelin' => [
                ModifierListingEnum::ABILITY_DEXTERITY->value => +2,
                ModifierListingEnum::ABILITY_CHARISMA->value => +2,
                ModifierListingEnum::ABILITY_STRENGTH->value => -2,
            ],
            'Nain' => [
                ModifierListingEnum::ABILITY_CONSTITUTION->value => +2,
                ModifierListingEnum::ABILITY_WISDOM->value => +2,
                ModifierListingEnum::ABILITY_CHARISMA->value => -2,
            ],
            'Gnome' => [
                ModifierListingEnum::ABILITY_CONSTITUTION->value => +2,
                ModifierListingEnum::ABILITY_CHARISMA->value => +2,
                ModifierListingEnum::ABILITY_STRENGTH->value => -2,
            ],
        ];

        $this->treatement($races, Race::class);
        $feats = [
            'Esquive' => [
                ModifierListingEnum::ARMOR_CLASS->value => +1,
            ],
            'Réflexes surhumains' => [
                ModifierListingEnum::SAVING_THROW_REFLEX->value => +2,
            ],
            'Vigueur surhumaine' => [
                ModifierListingEnum::SAVING_THROW_FORTITUDE->value => +2,
            ],
            'Fente' => [
                ModifierListingEnum::ARMOR_CLASS->value => -2,
            ],
        ];
        $this->treatement($feats, Feat::class);

        $skills = [
            'Elfe' => ['Perception' => +2],
            'Nain' => ['Perception' => +2],
            'Demi-elfe' => ['Perception' => +2],
            'Halfelin' => ['Perception' => +2, 'Acrobaties' => +4, 'Escalade' => +4],
            'Gnome' => ['Discrétion' => +4],
            'Demi-orque' => ['Intimidation' => +2],
        ];

        foreach ($skills as $raceName => $data) {
            foreach ($data as $skillName => $value) {
                if (!$race = $this->entityManager->getRepository(Race::class)->findOneByName($raceName)) {
                    $this->io->error('Race non trouvée '.$raceName);
                    continue;
                }
                if (!$skill = $this->entityManager->getRepository(Skill::class)->findOneByName($skillName)) {
                    $this->io->error('Skill non trouvée '.$skillName);
                    continue;
                }

                $abilityEnum = ModifierListingEnum::SKILL;

                if (!$modifier = $this->modifierRepository->findOneByIdClassNameAbilityAndRace(
                    $skill->getId(),
                    Skill::class,
                    $abilityEnum,
                    $race
                )) {
                    $modifier = new Modifier($skill->getId(), Skill::class);
                    $modifier->name = $skill->name;
                    $modifier->race = $race;
                    $this->modifierRepository->persist($modifier);
                }
                $modifier->value = $value;
                $modifier->ability = $abilityEnum;
            }
        }
    }

    public function importFiona()
    {
        $race = $this->entityManager->getRepository(Race::class)->findOneByName('Demi-orque');
        $class = $this->entityManager->getRepository(ClassT::class)->findOneByName('Guerrier');
        $level = $this->entityManager->getRepository(Level::class)->findOneByClassAndLevel($class, 1);
        $armor = $this->entityManager->getRepository(Armor::class)->findOneByName('Cotte de mailles');
        $weapon = $this->entityManager->getRepository(Weapon::class)->findOneByName('Cimeterre à deux mains');

        $character = new Character();
        $character->name = 'Fiona';
        $character->strength = 16;
        $character->dexterity = 12;
        $character->constitution = 12;
        $character->intelligence = 8;
        $character->charisma = 8;
        $character->race = $race;
        $character->classT = $class;
        $character->current_level = $level;
        $character->uuid = $character->generateUuid();

        $this->entityManager->persist($character);

        $characterArmor = new CharacterArmor($character, $armor);
        $characterWeapon = new CharacterWeapon($character, $weapon);

        $this->entityManager->persist($characterArmor);
        $this->entityManager->persist($characterWeapon);

        $this->entityManager->flush();
    }
}