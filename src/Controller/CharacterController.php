<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Ancestry\SizeEnum;
use AfmLibre\Pathfinder\Character\Form\CharacterEditType;
use AfmLibre\Pathfinder\Character\Form\CharacterType;
use AfmLibre\Pathfinder\Character\Message\CharacterCreated;
use AfmLibre\Pathfinder\Character\Message\CharacterUpdated;
use AfmLibre\Pathfinder\Character\Repository\CharacterLoader;
use AfmLibre\Pathfinder\Character\Repository\CharacterRepository;
use AfmLibre\Pathfinder\Classes\Repository\ClassFeatureRepository;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Helper\SessionHelper;
use AfmLibre\Pathfinder\Level\Repository\LevelRepository;
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
        private readonly CharacterLoader $characterLoader,
        private readonly ClassFeatureRepository $classFeatureRepository,
        private readonly LevelRepository $levelRepository,
        private readonly MessageBusInterface $dispatcher
    ) {
    }

    #[Route(path: '/', name: 'pathfinder_character_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $characterSelected = null;
        if ($uuid = $request->getSession()->get(SessionHelper::KEY_CHARACTER_SELECTED)) {
            $characterSelected = $this->characterRepository->findOneByUuid($uuid);
        }

        return $this->render(
            '@AfmLibrePathfinder/character/index.html.twig',
            [
                'characters' => $this->characterRepository->searchByUser(),
                'characterSelected' => $characterSelected,
            ]
        );
    }

    #[Route(path: '/new', name: 'pathfinder_character_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $character = new Character();
        $character->sizeType = SizeEnum::Medium;
        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $character->uuid = $character->generateUuid();
            $character->current_level = $this->levelRepository->findOneByClassAndLevel(
                $character->classT,
                $character->select_level
            );
            $this->characterRepository->persist($character);
            $this->characterRepository->flush();
            $this->dispatcher->dispatch(new CharacterCreated($character->getId()));

            return $this->redirectToRoute('pathfinder_character_show', ['uuid' => $character->uuid]);
        }

        return $this->render(
            '@AfmLibrePathfinder/character/new.html.twig',
            [
                'character' => $character,
                'form' => $form,
            ]
        );
    }

    #[Route(path: '/{uuid}', name: 'pathfinder_character_show', methods: ['GET'])]
    public function show(Character $character): Response
    {
        $characterDto = $this->characterLoader->load($character);

        $context = get_object_vars($characterDto);
        $context['character'] = $character;
        $context['characterDto'] = $characterDto;

        return $this->render(
            '@AfmLibrePathfinder/character/show.html.twig',
            $context
        );
    }

    #[Route(path: '/{uuid}/edit', name: 'pathfinder_character_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Character $character): Response
    {
        $form = $this->createForm(CharacterEditType::class, $character);
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
                'form' => $form,
            ]
        );
    }

    #[Route(path: '/select/{uuid}', name: 'pathfinder_character_select', methods: ['GET'])]
    public function select(Request $request, Character $character): Response
    {
        $request->getSession()->set(SessionHelper::KEY_CHARACTER_SELECTED, $character->uuid);

        $this->addFlash('success', 'Your character is selected');

        return $this->redirectToRoute('pathfinder_character_show', ['uuid' => $character->uuid]);
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
