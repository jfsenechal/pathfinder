<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Classes\Repository\ClassFeatureRepository;
use AfmLibre\Pathfinder\Classes\Repository\ClassRepository;
use AfmLibre\Pathfinder\Classes\Repository\ClassSkillRepository;
use AfmLibre\Pathfinder\Classes\Repository\ClassSpellRepository;
use AfmLibre\Pathfinder\Entity\ClassT;
use AfmLibre\Pathfinder\Form\SearchNameType;
use AfmLibre\Pathfinder\Level\Repository\LevelRepository;
use AfmLibre\Pathfinder\Spell\Utils\SpellUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/class')]
class ClassController extends AbstractController
{
    public function __construct(
        private readonly ClassRepository $classTRepository,
        private readonly ClassSpellRepository $classSpellRepository,
        private readonly ClassFeatureRepository $classFeatureRepository,
        private readonly LevelRepository $levelRepository,
        private readonly ClassSkillRepository $classSkillRepository
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

        $classes = $this->classTRepository->searchByName($name);

        return $this->render(
            '@AfmLibrePathfinder/class/index.html.twig',
            [
                'classes' => $classes,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route(path: '/{id}', name: 'pathfinder_class_show')]
    public function show(ClassT $classT)
    {
        $spellsClass = $this->classSpellRepository->searchByNameAndClass(null, $classT);
        $countSpells = count($spellsClass);
        foreach ($levels = $this->levelRepository->findByClass($classT) as $level) {
            $level->features = $this->classFeatureRepository->findByClassLevelAndSrc($classT, $level, 'MJ');
        }

        $spells = SpellUtils::groupByLevel($spellsClass);
        $classSkills = $this->classSkillRepository->findByClass($classT);
        $skills = array_map(function ($classSkill) {
            return $classSkill->skill;
        }, $classSkills);

        return $this->render(
            '@AfmLibrePathfinder/class/show.html.twig',
            [
                'classT' => $classT,
                'spells' => $spells,
                'countSpells' => $countSpells,
                'levels' => $levels,
                'skills' => $skills,
            ]
        );
    }
}
