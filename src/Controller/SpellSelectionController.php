<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterSpell;
use AfmLibre\Pathfinder\Form\SearchSpellType;
use AfmLibre\Pathfinder\Form\SelectionType;
use AfmLibre\Pathfinder\Repository\SpellRepository;
use AfmLibre\Pathfinder\Spell\Handler\HandlerCharacterSelection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SpellController
 * @package AfmLibre\Pathfinder\Controller
 * @Route("/spell/selection")
 */
class SpellSelectionController extends AbstractController
{
    private $spellRepository;
    private HandlerCharacterSelection $handlerCharacterSelection;

    public function __construct(SpellRepository $spellRepository, HandlerCharacterSelection $handlerCharacterSelection)
    {
        $this->spellRepository = $spellRepository;
        $this->handlerCharacterSelection = $handlerCharacterSelection;
    }

    /**
     * @Route("/{id}", name="pathfinder_spell_selection_index")
     */
    public function index(Request $request, Character $character)
    {
        $class = $character->getCharacterClass();
        $form = $this->createForm(SearchSpellType::class, ['class' => $class]);
        $formSelection = $this->createForm(SelectionType::class);
        $spells = [];

        $form->handleRequest($request);
        $formSelection->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $spells = $this->spellRepository->searchByNameAndClassAndLevel(
                $data['name'],
                $data['class'],
                $data['level']
            );
        }

        if ($formSelection->isSubmitted() && $formSelection->isValid()) {
            $selection = $request->request->all();
            $this->handlerCharacterSelection->handle($character, $selection['spells']);
        }

        return $this->render(
            '@AfmLibrePathfinder/spell_selection/index.html.twig',
            [
                'character' => $character,
                'spells' => $spells,
                'form' => $form->createView(),
                'form_selection' => $formSelection->createView(),
            ]
        );
    }

}
