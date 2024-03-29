<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\Race;
use AfmLibre\Pathfinder\Modifier\ModifierListingEnum;
use AfmLibre\Pathfinder\Modifier\Repository\ModifierRepository;
use AfmLibre\Pathfinder\Race\Repository\RaceRepository;
use AfmLibre\Pathfinder\Race\Repository\RaceTraitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/race')]
class RaceController extends AbstractController
{
    public function __construct(
        private readonly RaceRepository $raceRepository,
        private readonly RaceTraitRepository $raceTraitRepository,
        private readonly ModifierRepository $modifierRepository
    ) {
    }

    #[Route(path: '/', name: 'pathfinder_race_index')]
    public function index(): Response
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
    public function show(Race $race): Response
    {
        $modifiers = $this->modifierRepository->findSkillByRace(ModifierListingEnum::SKILL, $race);
        $traits = $this->raceTraitRepository->findByRace($race);

        return $this->render(
            '@AfmLibrePathfinder/race/show.html.twig',
            [
                'race' => $race,
                'traits' => $traits,
                'modifiers' => $modifiers,
            ]
        );
    }
}
