<?php

namespace AfmLibre\Pathfinder\Skill;

use AfmLibre\Pathfinder\Ability\AbilityEnum;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\Modifier;
use AfmLibre\Pathfinder\Entity\Race;
use AfmLibre\Pathfinder\Entity\Skill;
use AfmLibre\Pathfinder\Modifier\ModifierCalculator;
use AfmLibre\Pathfinder\Modifier\ModifierListingEnum;
use AfmLibre\Pathfinder\Repository\ModifierRepository;
use AfmLibre\Pathfinder\Repository\ClassSkillRepository;
use AfmLibre\Pathfinder\Repository\SkillRepository;

class SkillCalculator
{
    /**
     * @var int[]
     */
    private array $ownedIds;

    public function __construct(
        private SkillRepository $skillRepository,
        private ClassSkillRepository $classSkillRepository,
        private ModifierRepository $modifierRepository,
    ) {
    }

    /**
     * @return SkillDto[]
     */
    public function calculate(Character $character): array
    {
        $skillsDto = [];

        $all = $this->skillRepository->findAllOrdered();
        $classSkills = $this->classSkillRepository->findByClass($character->classT);
        $this->ownedIds = array_map(function ($classSkill) {
            return $classSkill->skill->getId();
        }, $classSkills);

        foreach ($all as $skill) {
            $isTrained = $this->isTrained($skill->getId());
            $ability = AbilityEnum::returnByNameFr($skill->ability);
            $racials = $this->racials($skill, $character->race);
            $property = strtolower($ability->value);
            $skillsDto[] = new SkillDto(
                $skill->name,
                $skill->getId(),
                $isTrained,
                0,
                $ability->value,
                ModifierCalculator::abilityValueModifier($character->$property),
                $racials,
                []
            );
        }

        return $skillsDto;
    }

    private function isTrained(int $id): bool
    {
        return in_array($id, $this->ownedIds);
    }

    /**
     * @param Skill $skill
     * @return Modifier[]
     */
    private function racials(Skill $skill, Race $race): array
    {
        $modifiers = [];

        if ($modifier = $this->modifierRepository->findOneByIdClassNameAbilityAndRace(
            $skill->getId(),
            Skill::class,
            ModifierListingEnum::SKILL,
            $race
        )) {
            $modifiers[] = $modifier;
        }

        return $modifiers;
    }

}