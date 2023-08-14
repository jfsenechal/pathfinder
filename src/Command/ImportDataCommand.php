<?php

namespace AfmLibre\Pathfinder\Command;

use AfmLibre\Pathfinder\Ancestry\SizeEnum;
use AfmLibre\Pathfinder\Character\Handler\CharacterRemoveHandler;
use AfmLibre\Pathfinder\Entity\Armor;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterFeat;
use AfmLibre\Pathfinder\Entity\CharacterWeapon;
use AfmLibre\Pathfinder\Entity\ClassT;
use AfmLibre\Pathfinder\Entity\Feat;
use AfmLibre\Pathfinder\Entity\Level;
use AfmLibre\Pathfinder\Entity\Race;
use AfmLibre\Pathfinder\Entity\User;
use AfmLibre\Pathfinder\Entity\Weapon;
use AfmLibre\Pathfinder\Leveling\LevelingEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'pathfinder:import-data',
    description: 'Add a short description for your command',
)]
class ImportDataCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CharacterRemoveHandler $characterRemoveHandler,
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('remove', null, InputOption::VALUE_NONE, 'What kind of data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $remove = (bool)$input->getOption('remove');

        if ($remove) {
            foreach (
                $this->entityManager->getRepository(Character::class)->findAll() as $character) {
                $this->characterRemoveHandler->remove($character);
            }

            return (int) Command::SUCCESS;
        }
        $this->importFiona();
        $this->importUser();
        $this->entityManager->flush();

        return (int) Command::SUCCESS;
    }

    public function importFiona()
    {
        $race = $this->entityManager->getRepository(Race::class)->findOneByName('Demi-orque');
        $class = $this->entityManager->getRepository(ClassT::class)->findOneByName('Guerrier');
        $level = $this->entityManager->getRepository(Level::class)->findOneByClassAndLevel($class, 2);
        $armor = $this->entityManager->getRepository(Armor::class)->findOneByName('Cotte de mailles');
        $weapon = $this->entityManager->getRepository(Weapon::class)->findOneByName('Cimeterre à deux mains');
        $feat = $this->entityManager->getRepository(Feat::class)->findOneByName('Attaque en puissance');

        $character = new Character();
        $character->name = 'Fiona';
        $character->strength = 16;
        $character->dexterity = 12;
        $character->constitution = 14;
        $character->intelligence = 8;
        $character->charisma = 8;
        $character->point_by_level = LevelingEnum::INCREASE_LIFE->value;
        $character->sizeType = SizeEnum::Medium;
        $character->race = $race;
        $character->classT = $class;
        $character->current_level = $level;
        $character->armor = $armor;
        $character->uuid = $character->generateUuid();

        $this->entityManager->persist($character);

        $characterWeapon = new CharacterWeapon($character, $weapon);
        $characterFeat = new CharacterFeat($character, $feat);

        $this->entityManager->persist($characterWeapon);
        $this->entityManager->persist($characterFeat);

        $this->entityManager->flush();
        $this->importKathia();
    }

    public function importKathia()
    {
        $race = $this->entityManager->getRepository(Race::class)->findOneByName('Humain');
        $class = $this->entityManager->getRepository(ClassT::class)->findOneByName('Prêtre');
        $level = $this->entityManager->getRepository(Level::class)->findOneByClassAndLevel($class, 14);
        $armor = $this->entityManager->getRepository(Armor::class)->findOneByName('Armure de plaques');
        $weapon = $this->entityManager->getRepository(Weapon::class)->findOneByName('Gourdin');
        $feat1 = $this->entityManager->getRepository(Feat::class)->findOneByName('Esquive');
        $feat2 = $this->entityManager->getRepository(Feat::class)->findOneByName(
            'Efficacité des sorts accrue supérieure'
        );
        $feat3 = $this->entityManager->getRepository(Feat::class)->findOneByName('Efficacité des sorts accrue');
        $feat4 = $this->entityManager->getRepository(Feat::class)->findOneByName('Science de l\'initiative');
        $feat5 = $this->entityManager->getRepository(Feat::class)->findOneByName('Canalisation sélective');
        $feat6 = $this->entityManager->getRepository(Feat::class)->findOneByName('Canalisation supplémentaire');

        $character = new Character();
        $character->name = 'Kathia';
        $character->strength = 16;
        $character->dexterity = 12;
        $character->constitution = 12;
        $character->intelligence = 8;
        $character->charisma = 8;
        $character->point_by_level = LevelingEnum::INCREASE_LIFE->value;
        $character->sizeType = SizeEnum::Medium;
        $character->race = $race;
        $character->classT = $class;
        $character->current_level = $level;
        $character->armor = $armor;
        $character->uuid = $character->generateUuid();

        $this->entityManager->persist($character);

        $this->entityManager->persist(new CharacterWeapon($character, $weapon));
        $this->entityManager->persist(new CharacterFeat($character, $feat1));
        $this->entityManager->persist(new CharacterFeat($character, $feat2));
        $this->entityManager->persist(new CharacterFeat($character, $feat3));
        $this->entityManager->persist(new CharacterFeat($character, $feat4));
        $this->entityManager->persist(new CharacterFeat($character, $feat5));
        $this->entityManager->persist(new CharacterFeat($character, $feat6));

        $this->entityManager->flush();
    }

    private function importUser()
    {
        if ($this->entityManager->getRepository(User::class)->findOneBy(['email' => 'jf@marche.be'])) {
            return;
        }
        $user = new User();
        $user->name = 'Senechal';
        $user->first_name = 'Jf';
        $user->email = 'jf@marche.be';
        $user->password = $this->userPasswordHasher->hashPassword($user, 'homer');
        $user->roles = ['ROLE_PATHFINDER'];
        $this->entityManager->persist($user);
    }
}