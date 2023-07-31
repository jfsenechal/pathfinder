<?php

namespace AfmLibre\Pathfinder\Ability;

use AfmLibre\Pathfinder\Entity\Modifier;
use AfmLibre\Pathfinder\Modifier\ModifierCalculator;

class AbilityDto
{
    /**
     * @param string $name
     * @param int $base
     * @param int $baseValueModifier
     * @param Modifier[] $baseModifiers
     * @param Modifier[] $bonusModifiers
     */
    public function __construct(
        readonly string $name,
        readonly int $base,
        readonly int $baseValueModifier,
        readonly array $baseModifiers,
        readonly array $bonusModifiers
    ) {
    }

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