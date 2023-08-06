<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Character\Repository\CharacterRepository;
use AfmLibre\Pathfinder\Character\Repository\CharacterWeaponRepository;
use AfmLibre\Pathfinder\Entity\CharacterWeapon;
use AfmLibre\Pathfinder\Entity\Weapon;
use AfmLibre\Pathfinder\Weapon\Repository\WeaponRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/weapon')]
class WeaponController extends AbstractController
{
    public function __construct(
        private readonly WeaponRepository $weaponRepository,
        private readonly CharacterRepository $characterRepository,
        private readonly CharacterWeaponRepository $characterWeaponRepository
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

    #[Route(path: '/{id}/outfit', name: 'pathfinder_weapon_outfit')]
    public function outFit(Request $request, Weapon $weapon): Response
    {
        $characters = $this->characterRepository->findAll();
        $character = $characters[0];

        if ($this->characterWeaponRepository->finOneByCharacterAndArmor($character, $weapon)) {
            $this->addFlash('danger', 'Vous êtes déjà équipé de cette arme');

            return $this->redirectToRoute(
                'pathfinder_character_show',
                ['uuid' => $character->uuid]
            );
        }

        $item = new CharacterWeapon($character, $weapon);
        $this->characterWeaponRepository->persist($item);
        $this->characterWeaponRepository->flush();

        $this->addFlash('success', 'Arme équipée');

        return $this->redirectToRoute(
            'pathfinder_character_show',
            ['uuid' => $character->uuid],
            Response::HTTP_SEE_OTHER
        );
    }

}
