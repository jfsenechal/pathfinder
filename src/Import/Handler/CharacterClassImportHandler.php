<?php


namespace AfmLibre\Pathfinder\Import\Handler;


use AfmLibre\Pathfinder\Entity\CharacterClass;
use AfmLibre\Pathfinder\Entity\Level;
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
            if (!$this->characterClassRepository->findByName($classData['Nom']) instanceof CharacterClass) {
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
                $this->addLevels($characterClass, $classData);
                $io->writeln($characterClass->getName());
            }
        }
        $this->characterClassRepository->flush();
    }

    private function addLevels(CharacterClass $class, array $classData)
    {
        foreach ($classData['Progression'] as $levelData) {
            $level = new Level($class);
            $level->lvl = $levelData['Niveau'];
            $level->bab = (int)$levelData['BBA'];
            $level->will = (int)$levelData['Volonté'];
            $level->fortitude = (int)$levelData['Vigueur'];
            $level->reflex = (int)$levelData['Réflexes'];
            $level->maxSpellLvl = (int)$levelData['SortMax'];

            $this->characterClassRepository->persist($level);
        }
    }
}
