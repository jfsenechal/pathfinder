<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Character\Message\CharacterCreated;
use AfmLibre\Pathfinder\Character\Message\CharacterUpdated;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Form\CharacterType;
use AfmLibre\Pathfinder\Repository\CharacterRepository;
use AfmLibre\Pathfinder\Repository\CharacterSpellRepository;
use AfmLibre\Pathfinder\Spell\Utils\SpellUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/character")
 */
class CharacterController extends AbstractController
{
    private CharacterRepository $characterRepository;
    private CharacterSpellRepository $characterSpellRepository;

    public function __construct(
        CharacterRepository $characterRepository,
        CharacterSpellRepository $characterSpellRepository
    ) {
        $this->characterRepository = $characterRepository;
        $this->characterSpellRepository = $characterSpellRepository;
    }

    /**
     * @Route("/", name="pathfinder_character_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render(
            '@AfmLibrePathfinder/character/index.html.twig',
            [
                'characters' => $this->characterRepository->searchByUser(),
            ]
        );
    }

    /**
     * @Route("/new", name="pathfinder_character_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $character = new Character();
        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->characterRepository->persist($character);
            $this->characterRepository->flush();
            $this->dispatchMessage(new CharacterCreated($character->getId()));

            return $this->redirectToRoute('pathfinder_character_show', ['id' => $character->getId()]);
        }

        return $this->renderForm(
            '@AfmLibrePathfinder/character/new.html.twig',
            [
                'character' => $character,
                'form' => $form,
            ]
        );
    }

    /**
     * @Route("/{id}", name="pathfinder_character_show", methods={"GET"})
     */
    public function show(Character $character): Response
    {
        $characterSpells = $this->characterSpellRepository->findByCharacter($character);
        $characterSpells= SpellUtils::groupByLevel($characterSpells);

        return $this->render(
            '@AfmLibrePathfinder/character/show.html.twig',
            [
                'character' => $character,
                'characterSpells' => $characterSpells,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="pathfinder_character_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Character $character): Response
    {
        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->characterRepository->flush();
            $this->dispatchMessage(new CharacterUpdated($character->getId()));

            return $this->redirectToRoute('pathfinder_character_show', ['id' => $character->getId()]);
        }

        return $this->renderForm(
            '@AfmLibrePathfinder/character/edit.html.twig',
            [
                'character' => $character,
                'form' => $form,
            ]
        );
    }

    /**
     * @Route("/{id}", name="pathfinder_character_delete", methods={"POST"})
     */
    public function delete(Request $request, Character $character): Response
    {
        if ($this->isCsrfTokenValid('delete'.$character->getId(), $request->request->get('_token'))) {
            $id = $character->getId();
            $this->dispatchMessage(new CharacterUpdated($id));
            $this->characterRepository->remove($character);
            $this->characterRepository->flush();
        }

        return $this->redirectToRoute('pathfinder_character_index', [], Response::HTTP_SEE_OTHER);
    }
}
