<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\Armor;
use AfmLibre\Pathfinder\Repository\ArmorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/armor')]
class ArmorController extends AbstractController
{
    public function __construct(
        private readonly ArmorRepository $armorRepository
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
}
