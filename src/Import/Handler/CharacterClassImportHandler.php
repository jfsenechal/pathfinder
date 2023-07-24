<?php


namespace AfmLibre\Pathfinder\Import\Handler;


use AfmLibre\Pathfinder\Entity\CharacterClass;
use AfmLibre\Pathfinder\Repository\CharacterClassRepository;
use Symfony\Component\Console\Style\SymfonyStyle;

class CharacterClassImportHandler
{
    public function __construct(private readonly CharacterClassRepository $characterClassRepository)
    {
    }

    public function call(SymfonyStyle $io, array $classes)
    {
        foreach ($classes as $classData) {
            if (!$this->characterClassRepository->findByName(
                    $classData['Nom']
                ) instanceof CharacterClass) {
                $characterClass = new CharacterClass();
                $characterClass->setName($classData['Nom']);
                $die = preg_replace('/[^0-9]/', '', (string)$classData['DésDeVie']);
                $characterClass->dieOfLive = $die;
                $characterClass->description = $classData['Description'];
                $characterClass->source = $classData['Source'];
                $characterClass->reference = $classData['Référence'];
                $characterClass->ranksPerLevel = $classData['RangsParNiveau'];
                $characterClass->alignment = $classData['Alignement'];
                $this->characterClassRepository->persist($characterClass);
                $io->writeln($characterClass->getName());
            }
        }
        $this->characterClassRepository->flush();
    }
}
