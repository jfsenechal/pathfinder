<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Armor\Repository\ArmorRepository;
use AfmLibre\Pathfinder\Character\Repository\CharacterRepository;
use AfmLibre\Pathfinder\Entity\Armor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/armor')]
class ArmorController extends AbstractController
{
    public function __construct(
        private readonly ArmorRepository $armorRepository,
        private readonly CharacterRepository $characterRepository
    ) {
    }

    #[Route(path: '/', name: 'pathfinder_armor_index')]
    public function index(): Response
    {
        $armors = $this->armorRepository->findAllOrdered();

        return $this->render(
            '@AfmLibrePathfinder/armor/index.html.twig',
            [
                'armors' => $armors,
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
    public function outFit(Armor $armor): Response
    {
        $characters = $this->characterRepository->findAll();
        $character = $characters[0];

        if ($character->armor?->getId() === $armor->getId()) {
            $this->addFlash('danger', 'Vous êtes déjà équipé de cette armure');

            return $this->redirectToRoute(
                'pathfinder_character_show',
                ['uuid' => $character->uuid]
            );
        }

        $character->armor = $armor;
        $this->armorRepository->flush();

        $this->addFlash('success', 'Armure équipée');

        return $this->redirectToRoute(
            'pathfinder_character_show',
            ['uuid' => $character->uuid],
            Response::HTTP_SEE_OTHER
        );
    }
}
