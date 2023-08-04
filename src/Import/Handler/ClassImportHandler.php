<?php


namespace AfmLibre\Pathfinder\Import\Handler;

use AfmLibre\Pathfinder\Classes\Repository\ClassRepository;
use AfmLibre\Pathfinder\Entity\ClassSkill;
use AfmLibre\Pathfinder\Entity\ClassT;
use AfmLibre\Pathfinder\Entity\Level;
use AfmLibre\Pathfinder\Skill\Repository\SkillRepository;
use Symfony\Component\Console\Style\SymfonyStyle;

class ClassImportHandler
{
    private SymfonyStyle $io;

    public function __construct(
        private readonly ClassRepository $classTRepository,
        private readonly SkillRepository $skillRepository
    ) {
    }

    public function call(SymfonyStyle $io, array $classes)
    {
        $io->section('CLASSES');

        $this->io = $io;

        foreach ($classes as $classData) {
            if ($this->classTRepository->findOneByName($classData['Nom'])) {
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
            $this->addSkills($classT, $classData);
            $io->writeln($classT->name);
        }
        $this->classTRepository->flush();
    }

    private function addLevels(ClassT $class, array $classData): void
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

    private function addSkills(ClassT $class, array $classData): void
    {
        foreach ($classData['CompétencesDeClasse'] as $data) {
            foreach ($data as $name) {
                if (!$skill = $this->skillRepository->findOneByName($name)) {
                    $this->io->error('Skill non trouvé '.$name.' pour la classe '.$classData['Nom']);
                    continue;
                }
                $classSkill = new ClassSkill($skill, $class);
                $this->classTRepository->persist($classSkill);
            }
        }
    }

    //todo "BBA" => "+15/+10/+5"
    private function attack(string $bba)
    {
        if (str_contains("/", $bba)) {
            list($a, $b, $c) = explode('/', $bba);
        }
    }
}
