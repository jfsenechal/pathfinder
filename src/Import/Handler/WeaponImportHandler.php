<?php


namespace AfmLibre\Pathfinder\Import\Handler;

use AfmLibre\Pathfinder\Entity\Weapon;
use AfmLibre\Pathfinder\Entity\WeaponCategory;
use AfmLibre\Pathfinder\Weapon\Repository\WeaponCategoryRepository;
use AfmLibre\Pathfinder\Weapon\Repository\WeaponRepository;
use Exception;
use Symfony\Component\Console\Style\SymfonyStyle;

class WeaponImportHandler
{
    public function __construct(
        private readonly WeaponRepository $weaponRepository,
        private readonly WeaponCategoryRepository $weaponCategoryRepository
    ) {
    }

    public function call(SymfonyStyle $io, array $weapons)
    {
        $io->section('WEAPONS');

        $this->addCategories($weapons);

        foreach ($weapons as $data) {
            if ($this->weaponRepository->findOneByName($data['Nom']) instanceof Weapon) {
                continue;
            }
            $weapon = new Weapon();
            $weapon->name = $data['Nom'];
            $weapon->category = $this->weaponCategoryRepository->findOneByName($data['Catégorie']);
            $weapon->category2 = $data['Sous-catégorie'];
            $weapon->cost = $data['Prix'];
            $weapon->damageSmall = $data['DégâtsP'];
            $weapon->damageMedium = $data['DégâtsM'];
            $weapon->critical = $data['Critique'];
            $weapon->ranged = $data['Portée'];
            $weapon->weight = $data['Poids'];
            $weapon->typed = $data['Type'];
            $weapon->special = $data['Spécial'];
            $weapon->description = $data['Description'] ?? '';
            $weapon->descriptionHtml = $data['DescriptionHtml'] ?? '';
            $weapon->sourced = $data['Source'];
            $weapon->reference = $data['Référence'];
            $this->weaponRepository->persist($weapon);
            $io->writeln($weapon->name);
            try {
                $this->weaponRepository->flush();
            } catch (Exception $e) {
                $io->error($e->getMessage());
                var_dump($this->weaponRepository);
            }
        }
        $this->weaponRepository->flush();
    }

    public function addCategories(array $armors)
    {
        foreach ($armors as $data) {
            if (!$this->weaponCategoryRepository->findOneByName($data['Catégorie'])) {
                $category = new WeaponCategory($data['Catégorie']);
                $this->weaponCategoryRepository->persist($category);
                $this->weaponCategoryRepository->flush();
            }
        }
    }
}
