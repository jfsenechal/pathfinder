<?php

namespace AfmLibre\Pathfinder\Skill;

use AfmLibre\Pathfinder\Entity\Modifier;

/**
 * Skill modifier = modifier of the skill’s key ability score + proficiency bonus + other bonuses + penalties
 */
class SkillDto
{
    /**
     * @param Modifier[] $racialModifiers
     * @param Modifier[] $bonusModifiers
     */
    public function __construct(
        readonly string $name,
        readonly int $id,
        readonly bool $isTrained,
        readonly int $rank,
        readonly string $abilityName,
        readonly int $abilityValueModifier,
        readonly array $racialModifiers,
        readonly array $bonusModifiers
    ) {

    }

    public function total(): int
    {
        $racial = $this->rank;
        foreach ($this->racialModifiers as $racialModifier) {
            $racial += $racialModifier->value;
        }

        $bonus = 0;
        foreach ($this->bonusModifiers as $modifier) {
            $bonus += $modifier->value;
        }

        $bonusTrained = 0;
        if ($this->isTrained) {
            $bonusTrained = 3;
        }
        //$racialBonus = ModifierCalculator::abilityValueModifier($racial);

        return $this->abilityValueModifier + $racial + $bonusTrained;
    }
}