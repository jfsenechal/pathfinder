<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterSpell;
use AfmLibre\Pathfinder\Form\SearchSpellType;
use AfmLibre\Pathfinder\Form\SelectionType;
use AfmLibre\Pathfinder\Repository\CharacterSpellRepository;
use AfmLibre\Pathfinder\Repository\SpellRepository;
use AfmLibre\Pathfinder\Spell\Dto\SpellSelectionDto;
use AfmLibre\Pathfinder\Spell\Handler\HandlerCharacterSelection;
use AfmLibre\Pathfinder\Spell\Utils\SpellUtils;
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
    private CharacterSpellRepository $characterSpellRepository;

    public function __construct(
        SpellRepository $spellRepository,
        HandlerCharacterSelection $handlerCharacterSelection,
        CharacterSpellRepository $characterSpellRepository
    ) {
        $this->spellRepository = $spellRepository;
        $this->handlerCharacterSelection = $handlerCharacterSelection;
        $this->characterSpellRepository = $characterSpellRepository;
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
        $characterSpells = $this->characterSpellRepository->findByCharacter($character);
        $characterSpells2 = array_map(
            function ($characterSpell) {
                return $characterSpell->getSpell();
            },
            $characterSpells
        );

        $selection = new SpellSelectionDto($character, $characterSpells2);

        $formSelection = $this->createForm(
            SelectionType::class,
            $selection,
            [
                'spells' => $spellsForSelection,
            ]
        );
        $formSelection->handleRequest($request);

        if ($formSelection->isSubmitted() && $formSelection->isValid()) {
            $selection = $request->request->all();
            $this->handlerCharacterSelection->handle($character, $selection['spells']);
        }

        //  dump($characterSpells2);

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
