<?php


namespace AfmLibre\Pathfinder\Import\Handler;

use AfmLibre\Pathfinder\Entity\CharacterClass;
use AfmLibre\Pathfinder\Entity\ClassFeature;
use AfmLibre\Pathfinder\Repository\CharacterClassRepository;
use AfmLibre\Pathfinder\Repository\ClassFeatureRepository;
use Symfony\Component\Console\Style\SymfonyStyle;

class CharacterClassFeatureImportHandler
{
    public function __construct(private readonly CharacterClassRepository $characterClassRepository, private readonly ClassFeatureRepository $classFeatureRepository)
    {
    }

    public function call(SymfonyStyle $io, array $classeFeatures)
    {
        foreach ($classeFeatures as $data) {
            if (($characterClass = $this->characterClassRepository->findByName($data['Classe'])) instanceof CharacterClass) {
                $classeFeature = new ClassFeature($characterClass);
                $classeFeature->setName($data['Nom']);
                $classeFeature->setDescription($data['Description']);
                $classeFeature->setReference($data['Référence']);
                $classeFeature->setSource($data['Source']);
                $classeFeature->setLevel($data['Niveau']);
                if (isset($data['Auto'])) {
                    $classeFeature->setAuto((bool)$data['Auto']);
                }
                $this->classFeatureRepository->persist($classeFeature);
                $io->writeln($classeFeature->getName());
            } else {
                $io->error('Classe non trouvee '.$data['Nom']);
            }
        }
            $this->classFeatureRepository->flush();
    }
}
