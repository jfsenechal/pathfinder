<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Form\SearchSpellType;
use AfmLibre\Pathfinder\Repository\SpellRepository;
use AfmLibre\Pathfinder\Spell\Factory\FormFactory;
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
    private SpellRepository $spellRepository;
    private HandlerCharacterSelection $handlerCharacterSelection;
    private FormFactory $formFactory;

    public function __construct(
        SpellRepository $spellRepository,
        HandlerCharacterSelection $handlerCharacterSelection,
        FormFactory $formFactory
    ) {
        $this->spellRepository = $spellRepository;
        $this->handlerCharacterSelection = $handlerCharacterSelection;
        $this->formFactory = $formFactory;
    }

    /**
     * @Route("/{id}", name="pathfinder_spell_selection_index")
     */
    public function index(Request $request, Character $character)
    {
        $class = $character->getCharacterClass();
        $spellsForSelection = [];

        $form = $this->createForm(SearchSpellType::class, ['class' => $class]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $spellsForSelection = $this->spellRepository->searchByNameAndClassAndLevel(
                $data['name'],
                $data['class'],
                $data['level']
            );
        }

        $formSelection = $this->formFactory->createFormSelectionSpells($character, $spellsForSelection);
        $formSelection->handleRequest($request);

        if ($formSelection->isSubmitted() && $formSelection->isValid()) {
            $selection = $request->request->all();
            $this->handlerCharacterSelection->handle($character, $selection['spells']);
        }

        return $this->render(
            '@AfmLibrePathfinder/spell_selection/index.html.twig',
            [
                'character' => $character,
                'spells' => $spellsForSelection,
                'formSearch' => $form->createView(),
                'formSelection' => $formSelection->createView(),
            ]
        );
    }
}
