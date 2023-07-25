<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Form\SearchSpellType;
use AfmLibre\Pathfinder\Repository\SpellRepository;
use AfmLibre\Pathfinder\Spell\Factory\FormFactory;
use AfmLibre\Pathfinder\Spell\Handler\SpellSelectionHandler;
use AfmLibre\Pathfinder\Spell\Message\SpellSelectionUpdated;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/spell/selection')]
class SpellSelectionController extends AbstractController
{
    public function __construct(
        private readonly SpellRepository $spellRepository,
        private readonly SpellSelectionHandler $spellSelectionHandler,
        private readonly FormFactory $formFactory,
        private readonly MessageBusInterface $dispatcher
    ) {
    }

    #[Route(path: '/{uuid}/edit', name: 'pathfinder_spell_selection_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Character $character)
    {
        $class = $character->classT;

        $spellsForSelection = $this->spellRepository->findByClass($class);
        if (count($spellsForSelection) == 0) {
            $this->addFlash('warning', 'Aucun sort pour la classe ' . $character->classT);

            return $this->redirectToRoute('pathfinder_character_show', ['uuid' => $character->uuid]);
        }

        $data = ['name' => null, 'class' => $class, 'level' => null];
        $form = $this->createForm(SearchSpellType::class, $data);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
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
            $this->spellSelectionHandler->handle($character, $selection->getSpells());
            $this->dispatcher->dispatch(new SpellSelectionUpdated(0));

            return $this->redirectToRoute('pathfinder_spell_selection_edit', ['uuid' => $character->uuid]);
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

    #[Route(path: '/delete', name: 'pathfinder_spell_selection_delete', methods: ['POST'])]
    public function validDeleteSelection(Request $request)
    {
        if ($this->isCsrfTokenValid('deselection', $request->request->get('_token'))) {
            $characterSpellId = (int)$request->request->get('characterspellid');

            if (null === $characterSpellId) {
                $this->addFlash('danger', 'Sélection non trouvée');

                return $this->redirectToRoute('pathfinder_home');
            }

            if (($character = $this->spellSelectionHandler->delete(
                $characterSpellId
            )) instanceof Character) {
                $this->addFlash('success', 'La sélection bien été supprimée');

                return $this->redirectToRoute('pathfinder_character_show', ['uuid' => $character->uuid]);
            }
        }

        return $this->redirectToRoute('pathfinder_home');
    }
}
