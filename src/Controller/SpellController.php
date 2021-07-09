<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Form\SearchSpellType;
use AfmLibre\Pathfinder\Repository\SpellRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SpellController extends AbstractController
{
    private $spellRepository;

    public function __construct(SpellRepository $spellRepository)
    {
        $this->spellRepository = $spellRepository;
    }

    /**
     * @Route("/spell", name="spell_index")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(SearchSpellType::class);
        $spells = [];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $spells = $this->spellRepository->searchByNameAndClass($data['name'], $data['class']);
            dump($spells);
        }

        return $this->render(
            'spell/index.html.twig',
            [
                'spells' => $spells,
                'form' => $form->createView(),
            ]
        );
    }
}
