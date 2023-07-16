<?php


namespace AfmLibre\Pathfinder\Import\Handler;


use AfmLibre\Pathfinder\Entity\Race;
use AfmLibre\Pathfinder\Entity\RaceTrait;
use AfmLibre\Pathfinder\Repository\RaceRepository;
use AfmLibre\Pathfinder\Repository\RaceTraitRepository;
use Symfony\Component\Console\Style\SymfonyStyle;

;

class RaceImportHandler
{
    public function __construct(private readonly RaceRepository $raceRepository, private readonly RaceTraitRepository $raceTraitRepository)
    {
    }

    public function call(SymfonyStyle $io, array $races)
    {
        foreach ($races as $raceData) {
            $io->writeln($raceData['Nom']);
            $race = $this->createRace($raceData);
            $this->raceRepository->persist($race);
            foreach ($raceData['Traits'] as $row) {
                $raceTrait = new RaceTrait($race);
                $raceTrait->setName($row['Nom']);
                $raceTrait->setDescription($row['Description']);
                $this->raceTraitRepository->persist($raceTrait);
            }
        }
        $this->raceRepository->flush();
        $this->raceTraitRepository->flush();
    }

    private function createRace(array $data): Race
    {
        $race = new Race();
        $race->setName($data['Nom']);
        $race->setReference($data['Référence']);
        $race->setSource($data['Source']);

        return $race;
    }

}
