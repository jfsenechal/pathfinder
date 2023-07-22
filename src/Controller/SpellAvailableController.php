<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Character\SearchHelper;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Form\SearchSpellType;
use AfmLibre\Pathfinder\Repository\SpellRepository;
use AfmLibre\Pathfinder\Spell\Factory\FormFactory;
use AfmLibre\Pathfinder\Spell\Handler\SpellAvailableHandler;
use AfmLibre\Pathfinder\Spell\Message\SpellAvailableUpdated;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SpellController
 * @package AfmLibre\Pathfinder\Controller
 */
#[Route(path: '/spell/available')]
class SpellAvailableController extends AbstractController
{
    public function __construct(
        private readonly SpellRepository $spellRepository,
        private readonly SpellAvailableHandler $spellAvailableHandler,
        private readonly FormFactory $formFactory,
        private readonly SearchHelper $searchHelper,
        private readonly MessageBusInterface $dispatcher
    ) {
    }

    #[Route(path: '/{uuid}/edit', name: 'pathfinder_spell_available_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Character $character)
    {
        $class = $character->getCharacterClass();
        $keySearch = 'available_spells';

        $data = $this->searchHelper->getArgs($keySearch);
        if ($data === []) {
            $data = ['name' => null, 'class' => $class, 'level' => null];
        }

        $form = $this->createForm(SearchSpellType::class, $data);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->searchHelper->setArgs($keySearch, $data);
        }

        $spellsForAvailable = $this->spellRepository->searchByNameAndClassAndLevel(
            $data['name'],
            $data['class'],
            $data['level']
        );

        $formAvailable = $this->formFactory->createFormSelectionSpells($character, $spellsForAvailable);
        $formAvailable->handleRequest($request);

        if ($formAvailable->isSubmitted() && $formAvailable->isValid()) {
            $available = $formAvailable->getData();
            $this->spellAvailableHandler->handle($character, $available->getSpells());
            $this->$this->dispatcher->dispatch(new SpellAvailableUpdated());

            return $this->redirectToRoute('pathfinder_spell_available_edit', ['uuid' => $character->getUuid()]);
        }

        return $this->render(
            '@AfmLibrePathfinder/spell_available/index.html.twig',
            [
                'character' => $character,
                'spells' => $spellsForAvailable,
                'formSearch' => $form->createView(),
                'formSelection' => $formAvailable->createView(),
            ]
        );
    }

    #[Route(path: '/delete', name: 'pathfinder_spell_available_delete', methods: ['POST'])]
    public function validDeleteAvailable(Request $request)
    {
        if ($this->isCsrfTokenValid('deavailable', $request->request->get('_token'))) {

            $characterSpellId = (int)$request->request->get('characterspellid');

            if (null === $characterSpellId) {
                $this->addFlash('danger', 'Sélection non trouvée');

                return $this->redirectToRoute('pathfinder_home');
            }

            if (($character = $this->spellAvailableHandler->delete(
                    $characterSpellId
                )) instanceof Character) {
                $this->addFlash('success', 'La sélection bien été supprimée');

                return $this->redirectToRoute('pathfinder_character_show', ['uuid' => $character->getUuid()]);

            }
        }

        return $this->redirectToRoute('pathfinder_home');
    }

}
