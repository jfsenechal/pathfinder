<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\Spell;
use AfmLibre\Pathfinder\Form\SearchSpellType;
use AfmLibre\Pathfinder\Repository\SpellRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SpellController
 * @package AfmLibre\Pathfinder\Controller
 * @Route("/spell")
 */
class SpellController extends AbstractController
{
    private $spellRepository;

    public function __construct(SpellRepository $spellRepository)
    {
        $this->spellRepository = $spellRepository;
    }

    /**
     * @Route("/", name="pathfinder_spell_index")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(SearchSpellType::class);
        $spells = [];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $spells = $this->spellRepository->searchByNameAndClassAndLevel($data['name'], $data['class']);
        }

        return $this->render(
            '@AfmLibrePathfinder/spell/index.html.twig',
            [
                'spells' => $spells,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="pathfinder_spell_show")
     */
    public function show(Spell $spell)
    {
        return $this->render(
            '@AfmLibrePathfinder/spell/show.html.twig',
            [
                'spell' => $spell,
            ]
        );
    }
}
