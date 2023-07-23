<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Repository\FeatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api')]
class ApiController extends AbstractController
{
    public function __construct(private FeatRepository $featRepository)
    {
    }

    #[Route(path: '/setpath/', name: 'pathfinder_api_set_pathfinder')]
    public function index(Request $request): JsonResponse
    {
        $id = $request->get('id');
        if ($feat = $this->featRepository->find($id)) {
            if ($feat->campaings && count($feat->campaings) > 0) {
                $feat->campaings = [];
            } else {
                $feat->campaings = ['pathfinder-1'];
            }
            $this->featRepository->flush();

            return $this->json($feat->campaings);
        }

        return $this->json(['oups']);
    }
}
