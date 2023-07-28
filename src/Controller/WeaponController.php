<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\Weapon;
use AfmLibre\Pathfinder\Repository\WeaponRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/weapon')]
class WeaponController extends AbstractController
{
    public function __construct(
        private readonly WeaponRepository $weaponRepository
    ) {
    }

    #[Route(path: '/', name: 'pathfinder_weapon_index')]
    public function index(): Response
    {
        $weapons = $this->weaponRepository->findAllOrdered();

        return $this->render(
            '@AfmLibrePathfinder/weapon/index.html.twig',
            [
                'weapons' => $weapons,
            ]
        );
    }

    #[Route(path: '/{id}/show', name: 'pathfinder_weapon_show')]
    public function show(Weapon $weapon): Response
    {
        return $this->render(
            '@AfmLibrePathfinder/weapon/show.html.twig',
            [
                'weapon' => $weapon,
            ]
        );
    }

}
