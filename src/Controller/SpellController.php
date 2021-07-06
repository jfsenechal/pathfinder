<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Repository\SpellRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index()
    {
        $spells = $this->spellRepository->findAll();
        return $this->render(
            'spell/index.html.twig',
            [
                'spells' => $spells,
            ]
        );
    }
}
