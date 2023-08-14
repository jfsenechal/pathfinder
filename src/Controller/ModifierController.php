<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\Modifier;
use AfmLibre\Pathfinder\Form\ModifierType;
use AfmLibre\Pathfinder\Modifier\Repository\ModifierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/modifier')]
class ModifierController extends AbstractController
{
    public function __construct(
        private readonly ModifierRepository $modifierRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route(path: '/', name: 'pathfinder_modifier_index')]
    public function index(): Response
    {
        $modifiers = $this->modifierRepository->findAllOrdered();

        return $this->render(
            '@AfmLibrePathfinder/modifier/index.html.twig',
            [
                'modifiers' => $modifiers,
            ]
        );
    }

    #[Route(path: '/{id}/show', name: 'pathfinder_modifier_show')]
    public function show(Modifier $modifier): Response
    {
        $object = $this->entityManager->getRepository($modifier->object_class)->find($modifier->object_id);

        return $this->render(
            '@AfmLibrePathfinder/modifier/show.html.twig',
            [
                'modifier' => $modifier,
                'object' => $object,
            ]
        );
    }

    #[Route(path: '/{id}/{className}/new', name: 'pathfinder_modifier_new')]
    public function new(Request $request, int $id, string $className): Response
    {
        $object = $this->entityManager->getRepository($className)->find($id);
        if (!$object) {
            $this->addFlash('danger', 'Objet non trouvé');

            return $this->redirectToRoute('pathfinder_home');
        }

        $modifier = new Modifier($id, $className);

        $form = $this->createForm(ModifierType::class, $modifier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modifier->name = $object->name;
            $this->modifierRepository->persist($modifier);
            $this->modifierRepository->flush();

            return $this->redirectToRoute('pathfinder_modifier_show', ['id' => $modifier->getId()]);
        }

        return $this->render(
            '@AfmLibrePathfinder/modifier/new.html.twig',
            [
                'form' => $form,
                'className' => $className,
            ]
        );
    }
}
