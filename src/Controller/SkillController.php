<?php

namespace AfmLibre\Pathfinder\Controller;

use AfmLibre\Pathfinder\Character\Repository\CharacterRepository;
use AfmLibre\Pathfinder\Character\Repository\CharacterSkillRepository;
use AfmLibre\Pathfinder\Classes\Repository\ClassSkillRepository;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterSkill;
use AfmLibre\Pathfinder\Entity\Skill;
use AfmLibre\Pathfinder\Skill\Repository\SkillRepository;
use AfmLibre\Pathfinder\Skill\SkillCalculator;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/skill')]
class SkillController extends AbstractController
{
    public function __construct(
        private readonly SkillRepository $skillRepository,
        private readonly CharacterRepository $characterRepository,
        private readonly CharacterSkillRepository $characterSkillRepository,
        private readonly ClassSkillRepository $classSkillRepository,
        private readonly SkillCalculator $skillCalculator
    ) {
    }

    #[Route(path: '/', name: 'pathfinder_skill_index')]
    public function index(): Response
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
    public function show(Skill $skill): Response
    {
        $classSkills = $this->classSkillRepository->findBySkill($skill);
        $classes = array_map(fn ($classSkill) => $classSkill->classT, $classSkills);

        return $this->render(
            '@AfmLibrePathfinder/skill/show.html.twig',
            [
                'skill' => $skill,
                'classes' => $classes,
            ]
        );
    }

    #[Route(path: '/{uuid}/edit', name: 'pathfinder_skill_character_edit')]
    public function edit(Character $character): Response
    {
        $skillsDto = $this->skillCalculator->calculate($character);
        $skillPointDto = $this->skillCalculator->points($character);

        return $this->render(
            '@AfmLibrePathfinder/skill/edit.html.twig',
            [
                'skills' => $skillsDto,
                'character' => $character,
                'skillPointDto' => $skillPointDto,
            ]
        );
    }

    #[Route(path: '/outfit/up', name: 'pathfinder_skill_outfit')]
    public function outFit(Request $request): JsonResponse
    {
        $uuid = $request->get('uuid');
        $id = $request->get('id');
        $points = $request->get('points');

        if (!$id && !$uuid & !$points) {
            return $this->json(['error' => ['uuid' => $uuid, 'id' => $id, 'points' => $points]]);
        }

        if ($points < 0) {
            return $this->json(['error' => 'points min 0']);
        }

        $skill = $this->skillRepository->find($id);
        $character = $this->characterRepository->findOneBy(['uuid' => $uuid]);

        if (!$character && $skill) {
            return $this->json(['error' => 'skill not found']);
        }

        if ($points > $character->current_level->lvl) {
            return $this->json(['error' => 'points max die of life']);
        }

        try {
            if (!$characterSkill = $this->characterSkillRepository->findByCharacterAndSkill($character, $skill)) {
                $characterSkill = new CharacterSkill($character, $skill);
                $this->characterSkillRepository->persist($characterSkill);
            }
            $characterSkill->point_spent = $points;
            $this->characterSkillRepository->flush();

            return $this->json(['ok' => ['uuid' => $uuid, 'id' => $id, 'points' => $points]]);
        } catch (Exception $exception) {
            return $this->json(['error' => $exception->getMessage()]);
        }
    }
}
