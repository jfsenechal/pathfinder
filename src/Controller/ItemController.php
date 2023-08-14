<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Item\Repository\ItemCategoryRepository;
use AfmLibre\Pathfinder\Item\Repository\ItemRepository;
use AfmLibre\Pathfinder\Entity\Item;
use AfmLibre\Pathfinder\Entity\ItemCategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/item')]
class ItemController extends AbstractController
{
    use GetCharacterTrait;

    public function __construct(
        private readonly ItemRepository $itemRepository,
        private readonly ItemCategoryRepository $itemCategoryRepository,
    ) {
    }

    #[Route(path: '/{id<\d+>?0}', name: 'pathfinder_item_index')]
    public function index(?ItemCategory $category = null): Response
    {
        $items = $category ? $this->itemRepository->findByCategory($category) : $this->itemRepository->findAllOrdered();
        $categories = $this->itemCategoryRepository->findAll();

        return $this->render(
            '@AfmLibrePathfinder/item/index.html.twig',
            [
                'items' => $items,
                'categories' => $categories,
                'category' => $category,
            ]
        );
    }

    #[Route(path: '/{id}/show', name: 'pathfinder_item_show')]
    public function show(Item $item): Response
    {
        return $this->render(
            '@AfmLibrePathfinder/item/show.html.twig',
            [
                'item' => $item,
            ]
        );
    }

    #[Route(path: '/{id}/outfit', name: 'pathfinder_item_outfit')]
    public function outFit(Request $request, Item $item): Response
    {
        if (($character = $this->hasCharacter($request)) instanceof Response) {
            return $character;
        }

            $this->addFlash('success', 'Armure équipée');
            $character->item = $item;

        $this->itemRepository->flush();

        return $this->redirectToRoute(
            'pathfinder_character_show',
            ['uuid' => $character->uuid],
            Response::HTTP_SEE_OTHER
        );
    }
}
