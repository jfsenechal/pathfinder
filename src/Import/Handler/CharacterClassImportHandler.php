<?php


namespace AfmLibre\Pathfinder\Import\Handler;


use AfmLibre\Pathfinder\Entity\CharacterClass;
use AfmLibre\Pathfinder\Repository\CharacterClassRepository;
use Symfony\Component\Console\Style\SymfonyStyle;

class CharacterClassImportHandler
{
    private CharacterClassRepository $characterClassRepository;

    public function __construct(CharacterClassRepository $characterClassRepository)
    {
        $this->characterClassRepository = $characterClassRepository;
    }

    public function call(SymfonyStyle $io,array $classes)
    {
        foreach ($classes as $classData) {
            if (!$this->characterClassRepository->findByName($classData['Nom'])) {
                $characterClass = new CharacterClass();
                $characterClass->setName($classData['Nom']);
                $die = preg_replace('/[^0-9]/', '', $classData['DésDeVie']);
                $characterClass->setDieOfLive($die);
                $this->characterClassRepository->persist($characterClass);
                $io->writeln($characterClass->getName());
            }
        }
        $this->characterClassRepository->flush();
    }
}
