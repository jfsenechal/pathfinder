<?php


namespace AfmLibre\Pathfinder\Import\Handler;


use AfmLibre\Pathfinder\Entity\Feat;
use AfmLibre\Pathfinder\Repository\FeatRepository;
use Symfony\Component\Console\Style\SymfonyStyle;

class FeatImportHandler
{
    public function __construct(
        private readonly FeatRepository $featRepository,
    ) {
    }

    public function call(SymfonyStyle $io, array $feats)
    {
        foreach ($feats as $featData) {
            $io->writeln($featData['Nom']);
            $feat = $this->createFeat($featData);
            $this->featRepository->persist($feat);
        }
        $this->featRepository->flush();
    }

    private function createFeat(array $data): Feat
    {
        $feat = new Feat();
        $feat->name =$data['Nom'];
        $feat->reference = $data['Référence'];
        $feat->source = $data['Source'];
        $feat->summary = $data['Résumé'] ?? null;
        $feat->category = $data['Catégorie'] ?? null;
        $feat->conditions = $data['Conditions'] ?? null;
        $feat->requires = $data['ConditionsRefs'] ?? [];
        $feat->advantage = $data['Avantage'] ?? null;
        $feat->advantageHtml = $data['AvantageHTML'] ?? null;
        $feat->normal = $data['Normal'] ?? null;
        $feat->special = $data['Spécial'] ?? null;

        return $feat;
    }

}
