<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\Spell;
use AfmLibre\Pathfinder\Spell\Form\SearchSpellType;
use AfmLibre\Pathfinder\Spell\Form\SpellEditFormType;
use AfmLibre\Pathfinder\Spell\Repository\SpellRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/spell')]
class SpellController extends AbstractController
{
    public function __construct(private readonly SpellRepository $spellRepository)
    {
    }

    #[Route(path: '/', name: 'pathfinder_spell_index')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(SearchSpellType::class);
        $spells = [];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $spells = $this->spellRepository->searchByNameAndClassAndLevel(
                $data['name'],
                $data['class'],
                $data['level']
            );
        }

        $search = $form->isSubmitted();

        return $this->render(
            '@AfmLibrePathfinder/spell/index.html.twig',
            [
                'spells' => $spells,
                'form' => $form,
                'search' => $search,
            ]
        );
    }

    #[Route(path: '/{id}/show', name: 'pathfinder_spell_show')]
    public function show(Spell $spell): Response
    {
        return $this->render(
            '@AfmLibrePathfinder/spell/show.html.twig',
            [
                'spell' => $spell,
            ]
        );
    }

    #[Route(path: '/{id}/edit', name: 'pathfinder_spell_edit')]
    public function edit(Request $request, Spell $spell): Response
    {
        $form = $this->createForm(SpellEditFormType::class, $spell);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->spellRepository->flush();

            return $this->redirectToRoute('pathfinder_spell_show', ['id' => $spell->getId()]);
        }

        return $this->render(
            '@AfmLibrePathfinder/spell/edit.html.twig',
            [
                'spell' => $spell,
                'form' => $form
            ]
        );
    }
}
