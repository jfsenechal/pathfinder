<?php


namespace AfmLibre\Pathfinder\Import\Handler;

use AfmLibre\Pathfinder\Entity\Armor;
use AfmLibre\Pathfinder\Entity\ArmorCategory;
use AfmLibre\Pathfinder\Repository\ArmorCategoryRepository;
use AfmLibre\Pathfinder\Repository\ArmorRepository;
use Symfony\Component\Console\Style\SymfonyStyle;

class ArmorImportHandler
{
    public function __construct(
        private readonly ArmorRepository $armorRepository,
        private readonly ArmorCategoryRepository $armorCategoryRepository
    ) {
    }

    public function call(SymfonyStyle $io, array $armors)
    {
        $this->addCategories($armors);

        foreach ($armors as $data) {
            if ($this->armorRepository->findByName($data['Nom']) instanceof Armor) {
                continue;
            }
            $armor = new Armor();
            $armor->name = $data['Nom'];
            $cateory = $this->armorCategoryRepository->findOneByName($data['Catégorie']);
            $armor->category = $cateory;
            $armor->cost = $data['Prix'];
            $armor->bonus = $data['Bonus'];
            $armor->bonusDexMax = $data['BonusDexMax'];
            $armor->penalty = $data['Malus'];
            $armor->castFail = $data['ÉchecProfane'];
            $armor->speed9 = $data['Vit9m'];
            $armor->speed6 = $data['Vit6m'];
            $armor->weight = $data['Poids'];
            $armor->description = $data['Description'] ?? '';
            $armor->descriptionHtml = $data['DescriptionHtml'] ?? '';
            $armor->sourced = $data['Source'];
            $armor->reference = $data['Référence'];
            $this->armorRepository->persist($armor);
            $io->writeln($armor->name);
        }
        $this->armorRepository->flush();
    }

    public function addCategories(array $armors)
    {
        foreach ($armors as $data) {
            if (!$this->armorCategoryRepository->findOneByName($data['Catégorie'])) {
                $category = new ArmorCategory($data['Catégorie']);
                $this->armorCategoryRepository->persist($category);
                $this->armorCategoryRepository->flush();
            }
        }
    }


}
