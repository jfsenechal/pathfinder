<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Form\CharacterType;
use AfmLibre\Pathfinder\Repository\CharacterRepository;
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

    public function __construct(
        CharacterRepository $characterRepository
    ) {
        $this->characterRepository = $characterRepository;
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

            return $this->redirectToRoute('pathfinder_character_index', [], Response::HTTP_SEE_OTHER);
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
        return $this->render(
            '@AfmLibrePathfinder/character/show.html.twig',
            [
                'character' => $character,
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

            return $this->redirectToRoute('pathfinder_character_index', [], Response::HTTP_SEE_OTHER);
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

            $this->characterRepository->remove($character);
            $this->characterRepository->flush();
        }

        return $this->redirectToRoute('pathfinder_character_index', [], Response::HTTP_SEE_OTHER);
    }
}
