<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Character\SearchHelper;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Form\SearchSpellType;
use AfmLibre\Pathfinder\Repository\SpellRepository;
use AfmLibre\Pathfinder\Spell\Factory\FormFactory;
use AfmLibre\Pathfinder\Spell\Handler\HandlerCharacterSelection;
use AfmLibre\Pathfinder\Spell\Message\SpellSelectionUpdated;
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
    private SearchHelper $searchHelper;

    public function __construct(
        SpellRepository $spellRepository,
        HandlerCharacterSelection $handlerCharacterSelection,
        FormFactory $formFactory,
        SearchHelper $searchHelper
    ) {
        $this->spellRepository = $spellRepository;
        $this->handlerCharacterSelection = $handlerCharacterSelection;
        $this->formFactory = $formFactory;
        $this->searchHelper = $searchHelper;
    }

    /**
     * @Route("/{id}/edit", name="pathfinder_spell_selection_index", methods={"GET","POST"})
     */
    public function index(Request $request, Character $character)
    {
        $class = $character->getCharacterClass();
        $keySearch = 'selection_spells';

        $data = $this->searchHelper->getArgs($keySearch);
        if (count($data) === 0) {
            $data = ['name' => null, 'class' => $class, 'level' => null];
        }

        $form = $this->createForm(SearchSpellType::class, $data);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->searchHelper->setArgs($keySearch, $data);
        }

        $spellsForSelection = $this->spellRepository->searchByNameAndClassAndLevel(
            $data['name'],
            $data['class'],
            $data['level']
        );

        $formSelection = $this->formFactory->createFormSelectionSpells($character, $spellsForSelection);
        $formSelection->handleRequest($request);

        if ($formSelection->isSubmitted() && $formSelection->isValid()) {
            $selection = $formSelection->getData();
            $this->handlerCharacterSelection->handle($character, $selection->getSpells());
            $this->dispatchMessage(new SpellSelectionUpdated());

            return $this->redirectToRoute('pathfinder_spell_selection_index', ['id' => $character->getId()]);
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

    /**
     * @Route("/delete", name="pathfinder_spell_selection_delete", methods={"POST"})
     */
    public function validDeleteSelection(Request $request)
    {
        if ($this->isCsrfTokenValid('deselection', $request->request->get('_token'))) {

            $characterSpellId = (int)$request->request->get('characterspellid');

            if (null === $characterSpellId) {
                $this->addFlash('danger', 'Sélection non trouvée');

                return $this->redirectToRoute('pathfinder_home');
            }

            if ($character = $this->handlerCharacterSelection->delete($characterSpellId)) {
                $this->addFlash('success', 'La sélection bien été supprimée');

                return $this->redirectToRoute('pathfinder_character_show', ['id' => $character->getId()]);

            }
        }

        return $this->redirectToRoute('pathfinder_home');
    }

}
