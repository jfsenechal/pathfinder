<?php

namespace AfmLibre\Pathfinder\Ability;

class AttackAbility
{
    public function __construct(
        readonly string $name,
        readonly int $bab,
        readonly int $caracteristic,
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
    public function total(): int
    {
        return $this->bab + $this->caracteristic + $this->sizeModifier + $this->rangePenalty;
    }
}