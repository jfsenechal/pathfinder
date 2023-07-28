<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\ArmorCategory;
use AfmLibre\Pathfinder\Repository\ArmorCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/armor-category')]
class ArmorCategoryController extends AbstractController
{
    public function __construct(
        private readonly ArmorCategoryRepository $armorCategoryRepository
    ) {
    }

    #[Route(path: '/', name: 'pathfinder_armor_category_index')]
    public function index(): Response
    {
        $categories = $this->armorCategoryRepository->findAllOrdered();

        return $this->render(
            '@AfmLibrePathfinder/armor_category/index.html.twig',
            [
                'categories' => $categories,
            ]
        );
    }

    #[Route(path: '/{id}/show', name: 'pathfinder_armor_show')]
    public function show(ArmorCategory $armorCategory): Response
    {
        return $this->render(
            '@AfmLibrePathfinder/armor_category/show.html.twig',
            [
                'armorCategory' => $armorCategory,
            ]
        );
    }
}
