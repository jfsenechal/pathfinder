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
        private readonly ItemRepository $itemRepository,
        private readonly ItemCategoryRepository $itemCategoryRepository
    ) {
    }

    public function call(SymfonyStyle $io, array $items)
    {
        $io->section('ITEMS');

        $this->addCategories($items);

        foreach ($items as $data) {
            if ($this->itemRepository->findOneByName($data['Nom']) instanceof Item) {
                continue;
            }
            $item = new Item();
            $item->name = $data['Nom'];
            $category = $this->itemCategoryRepository->findOneByName($data['Catégorie']);
            $item->category = $category;
            $item->cost = $data['Prix'];
            $item->weight = $data['Poids'];
            $item->description = $data['Description'] ?? '';
            $item->descriptionHtml = $data['DescriptionHtml'] ?? '';
            $item->sourced = $data['Source'] ?? '';
            $item->reference = $data['Référence'];
            $this->itemRepository->persist($item);
            $io->writeln($item->name);
        }
        $this->itemRepository->flush();
    }

    public function addCategories(array $items)
    {
        foreach ($items as $data) {
            if (!$this->itemCategoryRepository->findOneByName($data['Catégorie'])) {
                $category = new ItemCategory($data['Catégorie']);
                $this->itemCategoryRepository->persist($category);
                $this->itemCategoryRepository->flush();
            }
        }
    }

}
