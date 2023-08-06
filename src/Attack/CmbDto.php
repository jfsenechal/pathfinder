<?php

namespace AfmLibre\Pathfinder\Attack;

/**
 * Combat Maneuver Bonus
 */
class CmbDto
{
    public function __construct(
        readonly string $name,
        readonly int $bab,
        readonly int $strengthModifier,
        readonly int $sizeModifier
    ) {
    }

    public function total(): int
    {
        return $this->bab + $this->strengthModifier + $this->sizeModifier;
    }
}