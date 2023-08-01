<?php

namespace AfmLibre\Pathfinder\Skill;

/**
 * Skill modifier = modifier of the skill’s key ability score + proficiency bonus + other bonuses + penalties
 */
class SkillDto
{
    public function __construct(
        readonly string $name,
        readonly int $id,
        readonly bool $trained,
        readonly int $base,
        readonly string $abilityName,
        readonly int $abilityValueModifier,
        readonly array $baseModifiers,
        readonly array $bonusModifiers
    ) {

    }

//Total Carac Classe Rangs Modificateurs

    public function total(): int
    {
        $baseFull = $this->base;
        foreach ($this->baseModifiers as $baseModifier) {
            $baseFull += $baseModifier->value;
        }
        $bonusBase = ModifierCalculator::abilityValueModifier($baseFull);
        $bonus = 0;
        foreach ($this->bonusModifiers as $modifier) {
            $bonus += $modifier->value;
        }

        return $bonusBase + $bonus;
    }
}