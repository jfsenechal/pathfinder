<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Form\LevelCrossingType;
use AfmLibre\Pathfinder\Level\Message\LevelCrossingUpdated;
use AfmLibre\Pathfinder\Repository\ClassFeatureRepository;
use AfmLibre\Pathfinder\Repository\LevelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/level')]
class LevelingUpController extends AbstractController
{
    public function __construct(
        private LevelRepository $levelRepository,
        private ClassFeatureRepository $classFeatureRepository,
        private readonly MessageBusInterface $dispatcher
    ) {
    }

    #[Route('crossing/{uuid}', name: 'pathfinder_level_crossing_up')]
    public function index(Request $request, Character $character): Response
    {
        $class = $character->classT;
        foreach ($levels = $this->levelRepository->findByClass($class) as $level) {
            $level->features = $this->classFeatureRepository->findByClassAndLevel($class, $level);
        }

        $nextLevel = $this->levelRepository->findOneByClassAndLevel($class, $character->current_level->lvl + 1);
        $nextLevel->features = $this->classFeatureRepository->findByClassAndLevel($class, $level);

        $form = $this->createForm(LevelCrossingType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->levelRepository->flush();
            $this->dispatcher->dispatch(new LevelCrossingUpdated($character->id));

            return $this->redirectToRoute('pathfinder_character_show', ['uuid' => $character->uuid]);
        }

        return $this->render('@AfmLibrePathfinder/level/crossing.html.twig', [
            'levels' => $levels,
            'character' => $character,
            'nextLevel' => $nextLevel,
        ]);
    }
}
