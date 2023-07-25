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

#[Route(path: '/spell/profile')]
class SpellProfileController extends AbstractController
{
    public function __construct(
        private readonly SpellProfileRepository $spellProfileRepository,
        private readonly SpellProfileHandler $spellProfileHandler
    ) {
    }

    #[Route(path: '/{uuid}/index', name: 'pathfinder_spell_profile_index', methods: ['GET', 'POST'])]
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

    #[Route(path: '/{uuid}/new', name: 'pathfinder_spell_profile_new', methods: ['GET', 'POST'])]
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

    #[Route(path: '/{uuid}/show', name: 'pathfinder_spell_profile_show', methods: ['GET'])]
    public function show(SpellProfile $spellProfile)
    {
        $character = $spellProfile->ch();
        $spellProfileCharacterSpells = $spellProfile->getSpellprofileCharacterSpells();

        $characterSpells = array_map(
            fn($spellProfileCharacterSpell) => $spellProfileCharacterSpell->getCharacterSpell(),
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

    #[Route(path: '/{uuid}/edit', name: 'pathfinder_spell_profile_edit', methods: ['GET', 'POST'])]
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

    #[Route(path: '/{uuid}/spells', name: 'pathfinder_spell_profile_spells_edit', methods: ['GET', 'POST'])]
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

    #[Route(path: '/{uuid}/delete', name: 'pathfinder_spell_profile_delete', methods: ['POST'])]
    public function delete(Request $request, SpellProfile $spellProfile)
    {
        if ($this->isCsrfTokenValid('deprofile', $request->request->get('_token'))) {

            $characterSpellId = (int)$request->request->get('characterspellid');

            if (null === $characterSpellId) {
                $this->addFlash('danger', 'Sélection non trouvée');

                return $this->redirectToRoute('pathfinder_home');
            }

            if (($character = $this->spellProfileHandler->delete(
                    $characterSpellId
                )) instanceof \AfmLibre\Pathfinder\Entity\Character) {
                $this->addFlash('success', 'La sélection bien été supprimée');

                return $this->redirectToRoute('pathfinder_character_show', ['uuid' => $character->getUuid()]);

            }
        }

        return $this->redirectToRoute('pathfinder_home');
    }

    #[Route(path: '/{uuid}/print', name: 'pathfinder_spell_profile_print', methods: ['GET', 'POST'])]
    public function print(Request $request, SpellProfile $spellProfile)
    {
        $spellProfileCharacterSpells = $spellProfile->getSpellprofileCharacterSpells();

        $characterSpells = array_map(
            fn($spellProfileCharacterSpell) => $spellProfileCharacterSpell->getCharacterSpell(),
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
