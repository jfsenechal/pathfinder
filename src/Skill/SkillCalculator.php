<?php

namespace AfmLibre\Pathfinder\Skill;

use AfmLibre\Pathfinder\Ability\AbilityEnum;
use AfmLibre\Pathfinder\Character\Repository\CharacterSkillRepository;
use AfmLibre\Pathfinder\Classes\Repository\ClassSkillRepository;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\Modifier;
use AfmLibre\Pathfinder\Entity\Race;
use AfmLibre\Pathfinder\Entity\Skill;
use AfmLibre\Pathfinder\Leveling\LevelingEnum;
use AfmLibre\Pathfinder\Modifier\ModifierCalculator;
use AfmLibre\Pathfinder\Modifier\ModifierListingEnum;
use AfmLibre\Pathfinder\Modifier\Repository\ModifierRepository;
use AfmLibre\Pathfinder\Skill\Repository\SkillRepository;

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
        private CharacterSkillRepository $characterSkillRepository
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
            $pointsSpent = 0;
            if ($characterSkill = $this->characterSkillRepository->findByCharacterAndSkill($character, $skill)) {
                $pointsSpent = $characterSkill->point_spent;
            }
            $skillsDto[] = new SkillDto(
                $skill->name,
                $skill->getId(),
                $isTrained,
                $pointsSpent,
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
     * @param Race $race
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

    public function points(Character $character): SkillPointDto
    {
        $class = $character->classT;
        $human = 0;
        $level = $character->current_level->lvl;
        if ($character->race == 'Humain') {
            $human = 1 * $level;
        }
        $bonusIncrease = 0;
        if ($character->point_by_level && $character->point_by_level === LevelingEnum::INCREASE_SKILL->value) {
            $bonusIncrease = 1 * $level;
        }

        $pointsSpent = $this->pointsSpent($character);

        return new SkillPointDto(
            ($class->ranksPerLevel * $level),
            ModifierCalculator::abilityValueModifier($character->intelligence),
            $human,
            $bonusIncrease,
            $pointsSpent
        );
    }

    public function pointsSpent(Character $character): int
    {
        $total = 0;
        $points = array_map(function ($characterSkill) use ($total) {
            return $characterSkill->point_spent;
        }, $this->characterSkillRepository->findByCharacter($character));

        foreach ($points as $point) {
            $total += $point;
        }

        return $total;
    }

}