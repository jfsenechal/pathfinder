<?php

namespace AfmLibre\Pathfinder\Skill;

use AfmLibre\Pathfinder\Ability\AbilityEnum;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\Modifier;
use AfmLibre\Pathfinder\Entity\Skill;
use AfmLibre\Pathfinder\Modifier\ModifierCalculator;
use AfmLibre\Pathfinder\Modifier\ModifierListingEnum;
use AfmLibre\Pathfinder\Repository\ModifierRepository;
use AfmLibre\Pathfinder\Repository\SkillClassRepository;
use AfmLibre\Pathfinder\Repository\SkillRepository;

class SkillCalculator
{
    /**
     * @var int[]
     */
    private array $ownedIds;

    public function __construct(
        private SkillRepository $skillRepository,
        private SkillClassRepository $skillClassRepository,
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
        $classSkills = $this->skillClassRepository->findByClass($character->classT);
        $this->ownedIds = array_map(function ($classSkill) {
            return $classSkill->skill->getId();
        }, $classSkills);

        foreach ($all as $skill) {
            $isTrained = $this->isTrained($skill->getId());
            $ability = AbilityEnum::returnByNameFr($skill->ability);
            $racials = $this->racials($skill);
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
    private function racials(Skill $skill): array
    {
        $modifiers = [];

        if ($modifier = $this->modifierRepository->findOneByIdClassNameAndAbility(
            $skill->getId(),
            Skill::class,
            ModifierListingEnum::SKILL
        )) {
            $modifiers[] = $modifier;
        }

        return $modifiers;
    }

}