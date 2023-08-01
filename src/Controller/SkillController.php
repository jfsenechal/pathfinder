<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Entity\Skill;
use AfmLibre\Pathfinder\Repository\ClassSkillRepository;
use AfmLibre\Pathfinder\Repository\SkillRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/skill')]
class SkillController extends AbstractController
{
    public function __construct(
        private readonly SkillRepository $skillRepository,
        private ClassSkillRepository $classSkillRepository
    ) {
    }

    #[Route(path: '/', name: 'pathfinder_skill_index')]
    public function index(Request $request)
    {
        $skills = $this->skillRepository->findAllOrdered();

        return $this->render(
            '@AfmLibrePathfinder/skill/index.html.twig',
            [
                'skills' => $skills,
            ]
        );
    }

    #[Route(path: '/{id}', name: 'pathfinder_skill_show')]
    public function show(Skill $skill)
    {
        $classSkills = $this->classSkillRepository->findBySkill($skill);
        $classes = array_map(function ($classSkill) {
            return $classSkill->classT;
        }, $classSkills);

        return $this->render(
            '@AfmLibrePathfinder/skill/show.html.twig',
            [
                'skill' => $skill,
                'classes' => $classes,
            ]
        );
    }
}
