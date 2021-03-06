<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\SpellProfile;
use AfmLibre\Pathfinder\Form\SpellProfileType;
use AfmLibre\Pathfinder\Repository\SpellProfileRepository;
use AfmLibre\Pathfinder\Spell\Form\SpellProfileSelectionFormType;
use AfmLibre\Pathfinder\Spell\Handler\SpellProfileHandler;
use AfmLibre\Pathfinder\Spell\Utils\SpellUtils;
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
    private SpellProfileHandler $spellProfileHandler;
    private SpellProfileRepository $spellProfileRepository;

    public function __construct(
        SpellProfileRepository $spellProfileRepository,
        SpellProfileHandler $spellProfileHandler
    ) {
        $this->spellProfileHandler = $spellProfileHandler;
        $this->spellProfileRepository = $spellProfileRepository;
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
        $spellProfileCharacterSpells = $spellProfile->getSpellprofileCharacterSpells();

        $characterSpells = array_map(
            function ($spellProfileCharacterSpell) {
                return $spellProfileCharacterSpell->getCharacterSpell();
            },
            $spellProfileCharacterSpells->toArray()
        );
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
        $form = $this->createForm(SpellProfileType::class, $spellProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->spellProfileRepository->flush();

            return $this->redirectToRoute('pathfinder_spell_profile_show', ['uuid' => $spellProfile->getUuid()]);
        }

        return $this->render(
            '@AfmLibrePathfinder/spell_profile/edit.html.twig',
            [
                'spellProfile' => $spellProfile,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{uuid}/spells", name="pathfinder_spell_profile_spells_edit", methods={"GET","POST"})
     */
    public function editSpells(Request $request, SpellProfile $spellProfile)
    {
        $character = $spellProfile->getCharacterPlayer();

        $spellProfileSelectionDto = $this->spellProfileHandler->init($spellProfile);

        $form = $this->createForm(
            SpellProfileSelectionFormType::class,
            $spellProfileSelectionDto,
            [
                'spells' => $spellProfileSelectionDto->getSpellsAvailable(),
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            dump($data);
         //   $this->spellProfileHandler->handle($spellProfile);
        }

        return $this->render(
            '@AfmLibrePathfinder/spell_profile/select_spells.html.twig',
            [
                'spellProfile' => $spellProfile,
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
                $this->addFlash('danger', 'S??lection non trouv??e');

                return $this->redirectToRoute('pathfinder_home');
            }

            if ($character = $this->spellProfileHandler->delete($characterSpellId)) {
                $this->addFlash('success', 'La s??lection bien ??t?? supprim??e');

                return $this->redirectToRoute('pathfinder_character_show', ['uuid' => $character->getUuid()]);

            }
        }

        return $this->redirectToRoute('pathfinder_home');
    }

    /**
     * @Route("/{uuid}/print", name="pathfinder_spell_profile_print", methods={"GET","POST"})
     */
    public function print(Request $request, SpellProfile $spellProfile)
    {
        $spellProfileCharacterSpells = $spellProfile->getSpellprofileCharacterSpells();

        $characterSpells = array_map(
            function ($spellProfileCharacterSpell) {
                return $spellProfileCharacterSpell->getCharacterSpell();
            },
            $spellProfileCharacterSpells->toArray()
        );
        $characterSpells = SpellUtils::groupByLevel($characterSpells);

        return $this->render(
            '@AfmLibrePathfinder/spell_profile/print.html.twig',
            [
                'spellProfile' => $spellProfile,
                'characterSpells' => $characterSpells,
            ]
        );

    }

}
