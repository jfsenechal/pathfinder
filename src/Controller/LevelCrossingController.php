<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Repository\ClassFeatureRepository;
use AfmLibre\Pathfinder\Repository\LevelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/level')]
class LevelCrossingController extends AbstractController
{
    public function __construct(
        private LevelRepository $levelRepository,
        private ClassFeatureRepository $classFeatureRepository
    ) {
    }

    #[Route('crossing/{uuid}', name: 'pathfinder_level_crossing')]
    public function index(Character $character): Response
    {
        $class = $character->classT;
        foreach ($levels = $this->levelRepository->findByClass($class) as $level) {
            $level->features = $this->classFeatureRepository->findByClassAndLevel($class, $level);
        }

        return $this->render('@AfmLibrePathfinder/level/Crossing.html.twig', [
            'levels' => $levels,
        ]);
    }
}
