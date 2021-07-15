<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Form\SearchHeaderNameType;
use AfmLibre\Pathfinder\Form\SearchNameType;
use AfmLibre\Pathfinder\Repository\CharacterClassRepository;
use AfmLibre\Pathfinder\Repository\SpellRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private $spellRepository;
    private $characterClassRepository;

    public function __construct(SpellRepository $spellRepository, CharacterClassRepository $characterClassRepository)
    {
        $this->spellRepository = $spellRepository;
        $this->characterClassRepository = $characterClassRepository;
    }

    /**
     * @Route("/", name="pathfinder_home")
     */
    public function index()
    {
        return $this->render(
            '@AfmLibrePathfinder/default/index.html.twig',
            [
            ]
        );
    }

    /**
     * @Route("/search/form", name="pathfinder_search_form")
     */
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
                'form' => $form->createView(),
            ]
        );
    }

    private function words()
    {
        $pretres = ['prêtre', 'Prê'];
    }

    private function getCharacterClass(string $class)
    {
        if (in_array($this->words(), $class)) {
            $this->getPretre();
        }
    }

    private function getPretre()
    {
        $this->characterClassRepository->findBy(['name' => 'Prêtre']);
    }
}
