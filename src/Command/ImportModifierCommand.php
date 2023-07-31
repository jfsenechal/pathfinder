<?php

namespace AfmLibre\Pathfinder\Command;

use AfmLibre\Pathfinder\Entity\Feat;
use AfmLibre\Pathfinder\Entity\Modifier;
use AfmLibre\Pathfinder\Entity\Race;
use AfmLibre\Pathfinder\Modifier\ModifierEnum;
use AfmLibre\Pathfinder\Repository\ModifierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
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
        private ModifierRepository $modifierRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        $this->import();
        $this->modifierRepository->flush();

        return Command::SUCCESS;
    }

    private function treatement(array $data, string $className)
    {
        foreach ($data as $name => $values) {
            $object = $this->entityManager->getRepository($className)->findByName($name);
            if (!$object) {
                $this->io->error('Objet non trouvé '.$className.' '.$name);
                continue;
            }
            foreach ($values as $ability => $value) {
                $abilityEnum = ModifierEnum::findByName($ability);

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
                ModifierEnum::ABILITY_DEXTERITY->value => +2,
                ModifierEnum::ABILITY_INTELLIGENCE->value => +2,
                ModifierEnum::ABILITY_CONSTITUTION->value => -2,
            ],
            'Halfelin' => [
                ModifierEnum::ABILITY_DEXTERITY->value => +2,
                ModifierEnum::ABILITY_CHARISMA->value => +2,
                ModifierEnum::ABILITY_STRENGTH->value => -2,
            ],
            'Nain' => [
                ModifierEnum::ABILITY_CONSTITUTION->value => +2,
                ModifierEnum::ABILITY_WISDOM->value => +2,
                ModifierEnum::ABILITY_CHARISMA->value => -2,
            ],
            'Gnome' => [
                ModifierEnum::ABILITY_CONSTITUTION->value => +2,
                ModifierEnum::ABILITY_CHARISMA->value => +2,
                ModifierEnum::ABILITY_STRENGTH->value => -2,
            ],
        ];

        $this->treatement($races, Race::class);
        $feats = [
            'Esquive' => [
                ModifierEnum::ARMOR_CLASS->value => +1,
            ],
            'Réflexes surhumains' => [
                ModifierEnum::SAVING_THROW_REFLEX->value => +2,
            ],
            'Vigueur surhumaine' => [
                ModifierEnum::SAVING_THROW_FORTITUDE->value => +2,
            ],
            'Fente' => [
                ModifierEnum::ARMOR_CLASS->value => -2,
            ],
        ];
        $this->treatement($feats, Feat::class);
    }
}
