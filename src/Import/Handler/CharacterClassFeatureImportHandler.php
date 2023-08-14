<?php

namespace AfmLibre\Pathfinder\Import\Handler;

use AfmLibre\Pathfinder\Classes\Repository\ClassFeatureRepository;
use AfmLibre\Pathfinder\Classes\Repository\ClassRepository;
use AfmLibre\Pathfinder\Entity\ClassFeature;
use AfmLibre\Pathfinder\Entity\ClassT;
use AfmLibre\Pathfinder\Level\Repository\LevelRepository;
use Symfony\Component\Console\Style\SymfonyStyle;

class CharacterClassFeatureImportHandler
{
    public function __construct(
        private readonly ClassRepository $classTRepository,
        private readonly ClassFeatureRepository $classFeatureRepository,
        private readonly LevelRepository $levelRepository
    ) {
    }

    public function call(SymfonyStyle $io, array $classeFeatures)
    {
        $io->section('CLASS FEATURES');

        foreach ($classeFeatures as $data) {
            $classT = $this->classTRepository->findOneByName($data['Classe']);
            if (!$classT instanceof ClassT) {
                $io->error('Classe non trouvee ' . $data['Nom']);
                continue;
            }
            if (!$level = $this->levelRepository->findOneByClassAndLevel($classT, $data['Niveau'])) {
                $io->error('Level non trouvee ' . $data['Nom']);
                continue;
            }
            $classeFeature = new ClassFeature($classT, $level);
            $classeFeature->name = $data['Nom'];
            $classeFeature->description = $data['Description'];
            $classeFeature->reference = $data['Référence'];
            $classeFeature->sourced = $data['Source'];
            if (isset($data['Auto'])) {
                $classeFeature->auto = (bool)$data['Auto'];
            }
            $this->classFeatureRepository->persist($classeFeature);
            $io->writeln($classeFeature->name);
        }
        $this->classFeatureRepository->flush();
    }
}
