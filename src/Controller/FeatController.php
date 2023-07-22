<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\Feat;
use AfmLibre\Pathfinder\Repository\FeatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/feat')]
class FeatController extends AbstractController
{
    public function __construct(private readonly FeatRepository $featRepository)
    {
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
        return $this->render(
            '@AfmLibrePathfinder/feat/show.html.twig',
            [
                'feat' => $feat,
            ]
        );
    }
}
