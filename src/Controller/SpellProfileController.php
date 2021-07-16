<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Character\SearchHelper;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\SpellProfile;
use AfmLibre\Pathfinder\Form\SearchSpellType;
use AfmLibre\Pathfinder\Form\SpellProfileSelectionType;
use AfmLibre\Pathfinder\Form\SpellProfileType;
use AfmLibre\Pathfinder\Repository\CharacterSpellRepository;
use AfmLibre\Pathfinder\Repository\SpellProfileRepository;
use AfmLibre\Pathfinder\Repository\SpellRepository;
use AfmLibre\Pathfinder\Spell\Factory\FormFactory;
use AfmLibre\Pathfinder\Spell\Handler\SpellAvailableHandler;
use AfmLibre\Pathfinder\Spell\Message\SpellAvailableUpdated;
use AfmLibre\Pathfinder\Spell\Utils\SpellUtils;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SpellController
 * @package AfmLibre\Pathfinder\Controller
 * @Route("/spell/profile")
 */
class SpellProfileController extends AbstractController
{
    private SpellRepository $spellRepository;
    private SpellAvailableHandler $handlerCharacterProfile;
    private FormFactory $formFactory;
    private SearchHelper $searchHelper;
    private SpellProfileRepository $spellProfileRepository;
    private CharacterSpellRepository $characterSpellRepository;

    public function __construct(
        SpellRepository $spellRepository,
        SpellProfileRepository $spellProfileRepository,
        SpellAvailableHandler $handlerCharacterProfile,
        CharacterSpellRepository $characterSpellRepository,
        FormFactory $formFactory,
        SearchHelper $searchHelper
    ) {
        $this->spellRepository = $spellRepository;
        $this->handlerCharacterProfile = $handlerCharacterProfile;
        $this->formFactory = $formFactory;
        $this->searchHelper = $searchHelper;
        $this->spellProfileRepository = $spellProfileRepository;
        $this->characterSpellRepository = $characterSpellRepository;
    }

    /**
     * @Route("/{uuid}/index", name="pathfinder_spell_profile_index", methods={"GET","POST"})
     */
    public function index(Character $character)
    {
        $profiles = $this->spellProfileRepository->searchByCharacter($character);

        return $this->render(
            '@AfmLibrePathfinder/spell_profile/index.html.twig',
            [
                'character' => $character,
                'profiles' => $profiles,
            ]
        );
    }

    /**
     * @Route("/{uuid}/new", name="pathfinder_spell_profile_new", methods={"GET","POST"})
     */
    public function new(Request $request, Character $character)
    {
        $spellProfile = new SpellProfile($character);

        $form = $this->createForm(SpellProfileType::class, $spellProfile);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->spellProfileRepository->persist($spellProfile);
            $this->spellProfileRepository->flush();

            return $this->redirectToRoute('pathfinder_spell_profile_index', ['uuid' => $character->getUuid()]);
        }

        return $this->render(
            '@AfmLibrePathfinder/spell_profile/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{uuid}/show", name="pathfinder_spell_profile_show", methods={"GET"})
     */
    public function show(SpellProfile $spellProfile)
    {
        $character = $spellProfile->getCharacterPlayer();
        $characterSpells = $spellProfile->getCharacterSpells();
        $characterSpells = SpellUtils::groupByLevel($characterSpells);

        return $this->render(
            '@AfmLibrePathfinder/spell_profile/show.html.twig',
            [
                'spellProfile' => $spellProfile,
                'character' => $character,
                'characterSpells' => $characterSpells,
            ]
        );
    }

    /**
     * @Route("/{uuid}/edit", name="pathfinder_spell_profile_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SpellProfile $spellProfile)
    {
        $character = $spellProfile->getCharacterPlayer();
        $characterSpells = $this->characterSpellRepository->findByCharacter($character);
        $spellProfile->setCharacterSpells(new ArrayCollection($characterSpells));
        $characterSpells = SpellUtils::groupByLevel($characterSpells);

        $form = $this->createForm(SpellProfileSelectionType::class, $spellProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profile = $form->getData();
            $this->handlerCharacterProfile->handle($character, $profile->getSpells());
            $this->dispatchMessage(new SpellAvailableUpdated());

            return $this->redirectToRoute('pathfinder_spell_profile_index', ['uuid' => $character->getUuid()]);
        }

        return $this->render(
            '@AfmLibrePathfinder/spell_profile/edit.html.twig',
            [
                'character' => $character,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{uuid}/delete", name="pathfinder_spell_profile_delete", methods={"POST"})
     */
    public function delete(Request $request, SpellProfile $spellProfile)
    {
        if ($this->isCsrfTokenValid('deprofile', $request->request->get('_token'))) {

            $characterSpellId = (int)$request->request->get('characterspellid');

            if (null === $characterSpellId) {
                $this->addFlash('danger', 'Sélection non trouvée');

                return $this->redirectToRoute('pathfinder_home');
            }

            if ($character = $this->handlerCharacterProfile->delete($characterSpellId)) {
                $this->addFlash('success', 'La sélection bien été supprimée');

                return $this->redirectToRoute('pathfinder_character_show', ['uuid' => $character->getUuid()]);

            }
        }

        return $this->redirectToRoute('pathfinder_home');
    }

}
