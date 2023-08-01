<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\Race;
use AfmLibre\Pathfinder\Modifier\ModifierListingEnum;
use AfmLibre\Pathfinder\Repository\ModifierRepository;
use AfmLibre\Pathfinder\Repository\RaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/race')]
class RaceController extends AbstractController
{
    public function __construct(
        private readonly RaceRepository $raceRepository,
        private readonly ModifierRepository $modifierRepository
    ) {
    }

    #[Route(path: '/', name: 'pathfinder_race_index')]
    public function index(Request $request)
    {
        $races = $this->raceRepository->findAllOrdered();

        return $this->render(
            '@AfmLibrePathfinder/race/index.html.twig',
            [
                'races' => $races,
            ]
        );
    }

    #[Route(path: '/{id}', name: 'pathfinder_race_show')]
    public function show(Race $race)
    {
        $modifiers = $this->modifierRepository->findSkillByRace(ModifierListingEnum::SKILL, $race);

        return $this->render(
            '@AfmLibrePathfinder/race/show.html.twig',
            [
                'race' => $race,
                'traits' => $race->traits,
                'modifiers' => $modifiers,
            ]
        );
    }
}
