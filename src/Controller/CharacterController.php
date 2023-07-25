<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Character\Message\CharacterCreated;
use AfmLibre\Pathfinder\Character\Message\CharacterUpdated;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Form\CharacterType;
use AfmLibre\Pathfinder\Repository\CharacterClassRepository;
use AfmLibre\Pathfinder\Repository\CharacterRepository;
use AfmLibre\Pathfinder\Repository\CharacterSpellRepository;
use AfmLibre\Pathfinder\Repository\ClassFeatureRepository;
use AfmLibre\Pathfinder\Repository\LevelRepository;
use AfmLibre\Pathfinder\Spell\Utils\SpellUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/character')]
class CharacterController extends AbstractController
{
    public function __construct(
        private readonly CharacterRepository $characterRepository,
        private readonly CharacterSpellRepository $characterSpellRepository,
        private readonly CharacterClassRepository $characterClassRepository,
        private readonly ClassFeatureRepository $classFeatureRepository,
        private readonly LevelRepository $levelRepository,
        private readonly MessageBusInterface $dispatcher
    ) {
    }

    #[Route(path: '/', name: 'pathfinder_character_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render(
            '@AfmLibrePathfinder/character/index.html.twig',
            [
                'characters' => $this->characterRepository->searchByUser(),
            ]
        );
    }

    #[Route(path: '/new', name: 'pathfinder_character_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $character = new Character();
        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $character->uuid = $character->generateUuid();
            $this->characterRepository->persist($character);
            $this->characterRepository->flush();
            $this->dispatcher->dispatch(new CharacterCreated($character->getId()));

            return $this->redirectToRoute('pathfinder_character_show', ['uuid' => $character->uuid]);
        }

        return $this->render(
            '@AfmLibrePathfinder/character/new.html.twig',
            [
                'character' => $character,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{uuid}', name: 'pathfinder_character_show', methods: ['GET'])]
    public function show(Character $character): Response
    {
        $characterSpells = $this->characterSpellRepository->findByCharacter($character);
        $characterClass = $character->characterClass;
        $countSpells = count($characterSpells);
        $characterSpells = SpellUtils::groupByLevel($characterSpells);
        foreach ($levels = $this->levelRepository->findByClass($characterClass) as $level) {
            $level->features = $this->classFeatureRepository->findByClassAndLevel($characterClass, $level);
        }

        return $this->render(
            '@AfmLibrePathfinder/character/show.html.twig',
            [
                'character' => $character,
                'characterSpells' => $characterSpells,
                'countSpells' => $countSpells,
                'levels' => $levels,
            ]
        );
    }

    #[Route(path: '/{uuid}/edit', name: 'pathfinder_character_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Character $character): Response
    {
        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->characterRepository->flush();
            $this->dispatcher->dispatch(new CharacterUpdated($character->id));

            return $this->redirectToRoute('pathfinder_character_show', ['uuid' => $character->uuid]);
        }

        return $this->render(
            '@AfmLibrePathfinder/character/edit.html.twig',
            [
                'character' => $character,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{uuid}', name: 'pathfinder_character_delete', methods: ['POST'])]
    public function delete(Request $request, Character $character): Response
    {
        if ($this->isCsrfTokenValid('delete'.$character->uuid, $request->request->get('_token'))) {
            $id = $character->uuid;
            $this->$this->dispatcher->dispatch(new CharacterUpdated($id));
            $this->characterRepository->remove($character);
            $this->characterRepository->flush();
        }

        return $this->redirectToRoute('pathfinder_character_index', [], Response::HTTP_SEE_OTHER);
    }
}
