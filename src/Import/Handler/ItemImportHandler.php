<?php

namespace AfmLibre\Pathfinder\Import\Handler;

use AfmLibre\Pathfinder\Item\Repository\ItemCategoryRepository;
use AfmLibre\Pathfinder\Item\Repository\ItemRepository;
use AfmLibre\Pathfinder\Entity\Item;
use AfmLibre\Pathfinder\Entity\ItemCategory;
use Symfony\Component\Console\Style\SymfonyStyle;

class ItemImportHandler
{
    public function __construct(
        private readonly ItemRepository $armorRepository,
        private readonly ItemCategoryRepository $armorCategoryRepository
    ) {
    }

    public function call(SymfonyStyle $io, array $armors)
    {
        $io->section('ITEMS');

        $this->addCategories($armors);

        foreach ($armors as $data) {
            if ($this->armorRepository->findByName($data['Nom']) instanceof Item) {
                continue;
            }
            $armor = new Item();
            $armor->name = $data['Nom'];
            $cateory = $this->armorCategoryRepository->findOneByName($data['Catégorie']);
            $armor->category = $cateory;
            $armor->cost = $data['Prix'];
            $armor->weight = $data['Poids'];
            $armor->description = $data['Description'] ?? '';
            $armor->descriptionHtml = $data['DescriptionHtml'] ?? '';
            $armor->sourced = $data['Source'] ?? '';
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
                $category = new ItemCategory($data['Catégorie']);
                $this->armorCategoryRepository->persist($category);
                $this->armorCategoryRepository->flush();
            }
        }
    }


}
