<?php

namespace AfmLibre\Pathfinder\Import\Handler;

use AfmLibre\Pathfinder\Entity\CharacterClass;
use AfmLibre\Pathfinder\Entity\ClassFeature;
use AfmLibre\Pathfinder\Repository\CharacterClassRepository;
use AfmLibre\Pathfinder\Repository\ClassFeatureRepository;
use AfmLibre\Pathfinder\Repository\LevelRepository;
use Symfony\Component\Console\Style\SymfonyStyle;

class CharacterClassFeatureImportHandler
{
    public function __construct(
        private readonly CharacterClassRepository $characterClassRepository,
        private readonly ClassFeatureRepository $classFeatureRepository,
        private readonly LevelRepository $levelRepository
    ) {
    }

    public function call(SymfonyStyle $io, array $classeFeatures)
    {
        foreach ($classeFeatures as $data) {
            $characterClass = $this->characterClassRepository->findByName($data['Classe']);
            if (!$characterClass instanceof CharacterClass) {
                $io->error('Classe non trouvee '.$data['Nom']);
                continue;
            }
            if (!$level = $this->levelRepository->findByClassAndLevel($characterClass, $data['Niveau'])) {
                $io->error('Level non trouvee '.$data['Nom']);
                continue;
            }
            $classeFeature = new ClassFeature($characterClass, $level);
            $classeFeature->setName($data['Nom']);
            $classeFeature->description = $data['Description'];
            $classeFeature->reference = $data['Référence'];
            $classeFeature->source = $data['Source'];
            if (isset($data['Auto'])) {
                $classeFeature->auto = (bool)$data['Auto'];
            }
            $this->classFeatureRepository->persist($classeFeature);
            $io->writeln($classeFeature->getName());
        }
        $this->classFeatureRepository->flush();
    }
}
