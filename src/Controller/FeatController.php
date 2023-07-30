<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\CharacterFeat;
use AfmLibre\Pathfinder\Entity\Feat;
use AfmLibre\Pathfinder\Repository\CharacterFeatRepository;
use AfmLibre\Pathfinder\Repository\CharacterRepository;
use AfmLibre\Pathfinder\Repository\FeatRepository;
use AfmLibre\Pathfinder\Repository\ModifierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/feat')]
class FeatController extends AbstractController
{
    public function __construct(
        private readonly FeatRepository $featRepository,
        private readonly CharacterFeatRepository $characterFeatRepository,
        private readonly CharacterRepository $characterRepository,
        private readonly ModifierRepository $modifierRepository
    ) {
    }

    #[Route(path: '/', name: 'pathfinder_feat_index')]
    public function index(Request $request)
    {
        $feats = $this->featRepository->findAllOrdered();

        return $this->render(
            '@AfmLibrePathfinder/feat/index.html.twig',
            [
                'feats' => $feats,
            ]
        );
    }

    #[Route(path: '/{id}', name: 'pathfinder_feat_show')]
    public function show(Feat $feat)
    {
        $modifiers = $this->modifierRepository->findByClassName($feat::class);

        return $this->render(
            '@AfmLibrePathfinder/feat/show.html.twig',
            [
                'feat' => $feat,
                'modifiers' => $modifiers,
            ]
        );
    }


    #[Route(path: '/{id}/outfit', name: 'pathfinder_feat_outfit')]
    public function outFit(Request $request, Feat $feat): Response
    {
        $characters = $this->characterRepository->findAll();
        $character = $characters[0];

        $item = new CharacterFeat($character, $feat);
        $this->characterFeatRepository->persist($item);
        $this->characterFeatRepository->flush();

        $this->addFlash('success', 'Don acquis');

        return $this->redirectToRoute(
            'pathfinder_character_show',
            ['uuid' => $character->uuid],
            Response::HTTP_SEE_OTHER
        );
    }
}
