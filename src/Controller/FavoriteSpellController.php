<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Form\SearchSpellForFavoritesType;
use AfmLibre\Pathfinder\Spell\Factory\FormFactory;
use AfmLibre\Pathfinder\Spell\Handler\FavoriteSpellHandler;
use AfmLibre\Pathfinder\Spell\Message\FavoriteSpellUpdated;
use AfmLibre\Pathfinder\Spell\Repository\SpellRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/spell/favorite')]
class FavoriteSpellController extends AbstractController
{
    public function __construct(
        private readonly SpellRepository $spellRepository,
        private readonly FavoriteSpellHandler $favoriteSpellHandler,
        private readonly FormFactory $formFactory,
        private readonly MessageBusInterface $dispatcher
    ) {
    }

    #[Route(path: '/{uuid}/edit', name: 'pathfinder_favorite_spell_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Character $character)
    {
        $class = $character->classT;

        $spellsForSelection = $this->spellRepository->findByClass($class);
        if (count($spellsForSelection) == 0) {
            $this->addFlash('warning', 'Aucun sort pour la classe ' . $character->classT);

            return $this->redirectToRoute('pathfinder_character_show', ['uuid' => $character->uuid]);
        }

        $data = ['name' => null, 'class' => $class, 'level' => null];
        $form = $this->createForm(SearchSpellForFavoritesType::class, $data);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
        }

        $spellsForSelection = $this->spellRepository->searchByNameAndClassAndLevel(
            $data['name'],
            $data['class'],
            $data['level']
        );

        $formSelection = $this->formFactory->createFormFavoriteSpells($character, $spellsForSelection);
        $formSelection->handleRequest($request);

        if ($formSelection->isSubmitted() && $formSelection->isValid()) {
            $favorite = $formSelection->getData();
            $this->favoriteSpellHandler->handle($character, $favorite->getSpells());
            $this->dispatcher->dispatch(new FavoriteSpellUpdated(0));

            return $this->redirectToRoute('pathfinder_favorite_spell_edit', ['uuid' => $character->uuid]);
        }

        return $this->render(
            '@AfmLibrePathfinder/favorite_spell/index.html.twig',
            [
                'character' => $character,
                'spells' => $spellsForSelection,
                'formSearch' => $form,
                'formSelection' => $formSelection,
            ]
        );
    }

    #[Route(path: '/delete', name: 'pathfinder_favorite_spell_delete', methods: ['POST'])]
    public function validRemoveFavorite(Request $request): RedirectResponse
    {
        if ($this->isCsrfTokenValid('removefavorite', $request->request->get('_token'))) {
            $characterSpellId = (int)$request->request->get('characterspellid');

            if (0 === $characterSpellId) {
                $this->addFlash('danger', 'Sélection non trouvée');

                return $this->redirectToRoute('pathfinder_home');
            }

            if (($character = $this->favoriteSpellHandler->delete(
                $characterSpellId
            )) instanceof Character) {
                $this->addFlash('success', 'La sélection bien été supprimée');

                return $this->redirectToRoute('pathfinder_character_show', ['uuid' => $character->uuid]);
            }
        }

        return $this->redirectToRoute('pathfinder_home');
    }
}
