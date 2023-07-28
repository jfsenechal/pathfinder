<?php


namespace AfmLibre\Pathfinder\Import\Handler;

use AfmLibre\Pathfinder\Entity\ClassT;
use AfmLibre\Pathfinder\Entity\Level;
use AfmLibre\Pathfinder\Repository\ClassRepository;
use Symfony\Component\Console\Style\SymfonyStyle;

class ClassImportHandler
{
    public function __construct(private readonly ClassRepository $classTRepository)
    {
    }

    public function call(SymfonyStyle $io, array $classes)
    {
        foreach ($classes as $classData) {
            if ($this->classTRepository->findByName($classData['Nom']) instanceof ClassT) {
                continue;
            }
            $classT = new ClassT();
            $classT->name = $classData['Nom'];
            $die = preg_replace('/[^0-9]/', '', (string)$classData['DésDeVie']);
            $classT->dieOfLive = $die;
            $classT->description = $classData['Description'];
            $classT->sourced = $classData['Source'];
            $classT->reference = $classData['Référence'];
            $classT->ranksPerLevel = $classData['RangsParNiveau'];
            $classT->alignment = $classData['Alignement'];
            $this->classTRepository->persist($classT);
            $this->addLevels($classT, $classData);
            $io->writeln($classT->name);
        }
        $this->classTRepository->flush();
    }

    private function addLevels(ClassT $class, array $classData)
    {
        foreach ($classData['Progression'] as $levelData) {
            $level = new Level($class);
            $level->lvl = $levelData['Niveau'];
            $level->bab = (int)$levelData['BBA'];
            $level->will = (int)$levelData['Volonté'];
            $level->fortitude = (int)$levelData['Vigueur'];
            $level->reflex = (int)$levelData['Réflexes'];
            $level->maxSpellLvl = (int)$levelData['SortMax'];

            $this->classTRepository->persist($level);
        }
    }
}
