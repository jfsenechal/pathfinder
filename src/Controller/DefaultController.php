<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Character\Repository\CharacterRepository;
use AfmLibre\Pathfinder\Form\SearchHeaderNameType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function __construct(
        private readonly CharacterRepository $characterRepository
    ) {
    }

    #[Route(path: '/', name: 'pathfinder_home')]
    public function index(): Response
    {
        $user = $this->getUser();
        $characters = $this->characterRepository->findByUser($user);

        return $this->render(
            '@AfmLibrePathfinder/default/index.html.twig',
            [
                'characters' => $characters,
            ]
        );
    }

    #[Route(path: '/search/form', name: 'pathfinder_search_form')]
    public function searchForm(): Response
    {
        $form = $this->createForm(
            SearchHeaderNameType::class,
            [],
            [
                'method' => 'GET',
                'action' => $this->generateUrl('pathfinder_search_form'),
            ]
        );

        return $this->render(
            '@AfmLibrePathfinder/_search_form.html.twig',
            [
                'form' => $form,
            ]
        );
    }
}
