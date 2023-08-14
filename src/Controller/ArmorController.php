<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Armor\ArmorEnum;
use AfmLibre\Pathfinder\Armor\Repository\ArmorCategoryRepository;
use AfmLibre\Pathfinder\Armor\Repository\ArmorRepository;
use AfmLibre\Pathfinder\Entity\Armor;
use AfmLibre\Pathfinder\Entity\ArmorCategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/armor')]
class ArmorController extends AbstractController
{
    use GetCharacterTrait;

    public function __construct(
        private readonly ArmorRepository $armorRepository,
        private readonly ArmorCategoryRepository $armorCategoryRepository,
    ) {
    }

    #[Route(path: '/{id<\d+>?0}', name: 'pathfinder_armor_index')]
    public function index(?ArmorCategory $category = null): Response
    {
        $armors = $category ? $this->armorRepository->findByCategory($category) : $this->armorRepository->findAllOrdered();
        $categories = $this->armorCategoryRepository->findAll();

        return $this->render(
            '@AfmLibrePathfinder/armor/index.html.twig',
            [
                'armors' => $armors,
                'categories' => $categories,
                'category' => $category,
            ]
        );
    }

    #[Route(path: '/{id}/show', name: 'pathfinder_armor_show')]
    public function show(Armor $armor): Response
    {
        return $this->render(
            '@AfmLibrePathfinder/armor/show.html.twig',
            [
                'armor' => $armor,
            ]
        );
    }

    #[Route(path: '/{id}/outfit', name: 'pathfinder_armor_outfit')]
    public function outFit(Request $request, Armor $armor): Response
    {
        if (($character = $this->hasCharacter($request)) instanceof Response) {
            return $character;
        }

        $shieldCategory = $this->armorCategoryRepository->findOneByName(ArmorEnum::ShieldFr->value);

        if ($armor->category->id === $shieldCategory->id) {
            $this->addFlash('success', 'Bouclier équipé');
            $character->shield = $armor;
        } else {
            $this->addFlash('success', 'Armure équipée');
            $character->armor = $armor;
        }

        $this->armorRepository->flush();

        return $this->redirectToRoute(
            'pathfinder_character_show',
            ['uuid' => $character->uuid],
            Response::HTTP_SEE_OTHER
        );
    }
}
