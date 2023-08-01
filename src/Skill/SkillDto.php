<?php

namespace AfmLibre\Pathfinder\Skill;

use AfmLibre\Pathfinder\Entity\Modifier;
use AfmLibre\Pathfinder\Modifier\ModifierCalculator;

/**
 * Skill modifier = modifier of the skill’s key ability score + proficiency bonus + other bonuses + penalties
 */
class SkillDto
{
    /**
     * @param string $name
     * @param int $id
     * @param bool $trained
     * @param int $base
     * @param string $abilityName
     * @param int $abilityValueModifier
     * @param Modifier[] $racialModifiers
     * @param Modifier[] $bonusModifiers
     */
    public function __construct(
        readonly string $name,
        readonly int $id,
        readonly bool $trained,
        readonly int $base,
        readonly string $abilityName,
        readonly int $abilityValueModifier,
        readonly array $racialModifiers,
        readonly array $bonusModifiers
    ) {

    }

    public function total(): int
    {
        $racial = $this->base;
        foreach ($this->racialModifiers as $racialModifier) {
            $racial += $racialModifier->value;
        }

        $bonus = 0;
        foreach ($this->bonusModifiers as $modifier) {
            $bonus += $modifier->value;
        }

        $bonusTrained = 0;
        if ($this->trained) {
            $bonusTrained = 3;
        }
        $racialBonus = ModifierCalculator::abilityValueModifier($racial);

        return $this->abilityValueModifier + $racialBonus + $bonusTrained;
    }
}