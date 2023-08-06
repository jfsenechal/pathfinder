<?php

namespace AfmLibre\Pathfinder\Attack;

/**
 * Melee attack roll result = d20 roll
 * + Strength modifier (or optionally Dexterity modifier for a finesse weapon) + proficiency bonus + other bonuses + penalties
 * Ranged attack roll result = d20 roll + Dexterity modifier + proficiency bonus + other bonuses + penalties
 */
class AttackRoll
{
    public function __construct(
        readonly string $name,
        readonly int $bab,
        readonly string $characteristicName,
        readonly int $characteristicModifier,
        readonly int $sizeModifier = 0,
        readonly int $rangePenalty = 0
    ) {
    }

    /**
     * Bonus d'attaque au corps à corps
     * Bonus de base à l’attaque + modificateur de Force + modificateur de taille
     *
     * Bonus d'attaque à distance
     * Bonus de base à l’attaque + modificateur de Dextérité + modificateur de taille + malus de portée
     */
    public function bonusAttack(): int
    {
        return $this->bab + $this->characteristicModifier + $this->sizeModifier + $this->rangePenalty;
    }

}