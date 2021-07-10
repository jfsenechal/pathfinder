<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\CharacterClass;
use AfmLibre\Pathfinder\Entity\Spell;
use AfmLibre\Pathfinder\Form\SearchSpellType;
use AfmLibre\Pathfinder\Repository\CharacterClassRepository;
use AfmLibre\Pathfinder\Repository\SpellClassLevelRepository;
use AfmLibre\Pathfinder\Repository\SpellRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SpellController
 * @package AfmLibre\Pathfinder\Controller
 * @Route("/characterclass")
 */
class CharacterClassController extends AbstractController
{
    private CharacterClassRepository $characterClassRepository;
    private SpellClassLevelRepository $spellClassLevelRepository;

    public function __construct(CharacterClassRepository $characterClassRepository, SpellClassLevelRepository $spellClassLevelRepository)
    {
        $this->characterClassRepository = $characterClassRepository;

        $this->spellClassLevelRepository = $spellClassLevelRepository;
    }

    /**
     * @Route("/", name="character_index")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(SearchSpellType::class);
        $characterClasses = [];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $characterClasses = $this->characterClassRepository->searchByName($data['name']);
        }

        return $this->render(
            '@AfmLibrePathfinder/character/index.html.twig',
            [
                'characterClasses' => $characterClasses,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="character_show")
     */
    public function show(CharacterClass $characterClass)
    {
        $spellsClass = $this->spellClassLevelRepository->searchByNameAndClass(null, $characterClass);

        return $this->render(
            '@AfmLibrePathfinder/character/show.html.twig',
            [
                'characterClass' => $characterClass,
                'spellsClass' => $spellsClass,
            ]
        );
    }
}
