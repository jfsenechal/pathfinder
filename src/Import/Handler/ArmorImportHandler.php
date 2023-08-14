<?php


namespace AfmLibre\Pathfinder\Import\Handler;

use AfmLibre\Pathfinder\Armor\Repository\ArmorCategoryRepository;
use AfmLibre\Pathfinder\Armor\Repository\ArmorRepository;
use AfmLibre\Pathfinder\Entity\Armor;
use AfmLibre\Pathfinder\Entity\ArmorCategory;
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
        $io->section('ARMORS');

        $this->addCategories($armors);

        foreach ($armors as $data) {
            if ($this->armorRepository->findOneByName($data['Nom']) instanceof Armor) {
                continue;
            }
            $armor = new Armor();
            $armor->name = $data['Nom'];
            $armor->category = $this->armorCategoryRepository->findOneByName($data['Catégorie']);
            $armor->cost = $data['Prix'];
            $armor->bonus = $data['Bonus'];
            $armor->bonus_dexterity_max = $data['BonusDexMax'] == '—' ?? null;
            $armor->penalty = $data['Malus'];
            $armor->cast_fail = $data['ÉchecProfane'];
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
