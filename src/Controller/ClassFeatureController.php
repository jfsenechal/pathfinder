<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\ClassFeature;
use AfmLibre\Pathfinder\Form\SearchNameType;
use AfmLibre\Pathfinder\Repository\ClassRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/class/feature')]
class ClassFeatureController extends AbstractController
{
    public function __construct(
        private readonly ClassRepository $classTRepository,
    ) {
    }

    #[Route(path: '/', name: 'pathfinder_class_feature_index')]
    public function index(Request $request)
    {
        $form = $this->createForm(SearchNameType::class);
        $name = null;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $name = $data['name'];
        }

        $classes = $this->classTRepository->searchByName($name);

        return $this->render(
            '@AfmLibrePathfinder/class/index.html.twig',
            [
                'classes' => $classes,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{id}', name: 'pathfinder_class_feature_show')]
    public function show(ClassFeature $classFeature)
    {
        return $this->render(
            '@AfmLibrePathfinder/class_feature/show.html.twig',
            [
                'classFeature' => $classFeature,
            ]
        );
    }
}
