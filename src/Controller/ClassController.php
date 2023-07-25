<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\ClassT;
use AfmLibre\Pathfinder\Form\SearchNameType;
use AfmLibre\Pathfinder\Repository\ClassFeatureRepository;
use AfmLibre\Pathfinder\Repository\ClassRepository;
use AfmLibre\Pathfinder\Repository\LevelRepository;
use AfmLibre\Pathfinder\Repository\SpellClassRepository;
use AfmLibre\Pathfinder\Spell\Utils\SpellUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/class')]
class ClassController extends AbstractController
{
    public function __construct(
        private readonly ClassRepository $classTRepository,
        private readonly SpellClassRepository $spellClassRepository,
        private readonly ClassFeatureRepository $classFeatureRepository,
        private readonly LevelRepository $levelRepository
    ) {
    }

    #[Route(path: '/', name: 'pathfinder_class_index')]
    public function index(Request $request)
    {
        $form = $this->createForm(SearchNameType::class);
        $name = null;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $name = $data['name'];
        }

        $classTes = $this->classTRepository->searchByName($name);

        return $this->render(
            '@AfmLibrePathfinder/class/index.html.twig',
            [
                'classTes' => $classTes,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{id}', name: 'pathfinder_class_show')]
    public function show(ClassT $classT)
    {
        $spellsClass = $this->spellClassRepository->searchByNameAndClass(null, $classT);
        $countSpells = count($spellsClass);
        foreach ($levels = $this->levelRepository->findByClass($classT) as $level) {
            $level->features = $this->classFeatureRepository->findByClassLevelAndSrc($classT, $level, 'MJ');
        }

        $spells = SpellUtils::groupByLevel($spellsClass);

        return $this->render(
            '@AfmLibrePathfinder/class/show.html.twig',
            [
                'classT' => $classT,
                'spells' => $spells,
                'countSpells' => $countSpells,
                'levels' => $levels,
            ]
        );
    }
}
