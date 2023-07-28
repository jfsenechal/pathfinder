<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\WeaponCategory;
use AfmLibre\Pathfinder\Repository\WeaponCategoryRepository;
use AfmLibre\Pathfinder\Repository\WeaponRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/weapon-category')]
class WeaponCategoryController extends AbstractController
{
    public function __construct(
        private readonly WeaponCategoryRepository $weaponCategoryRepository,
        private readonly WeaponRepository $weaponRepository
    ) {
    }

    #[Route(path: '/', name: 'pathfinder_weapon_category_index')]
    public function index(): Response
    {
        $categories = $this->weaponCategoryRepository->findAllOrdered();
        foreach ($categories as $category) {
            $category->weapons = $this->weaponRepository->findByCategory($category);
        }

        return $this->render(
            '@AfmLibrePathfinder/weapon_category/index.html.twig',
            [
                'categories' => $categories,
            ]
        );
    }

    #[Route(path: '/{id}/show', name: 'pathfinder_weapon_category_show')]
    public function show(WeaponCategory $weaponCategory): Response
    {
        return $this->render(
            '@AfmLibrePathfinder/weapon_category/show.html.twig',
            [
                'category' => $weaponCategory,
            ]
        );
    }

}
