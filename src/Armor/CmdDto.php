<?php

namespace AfmLibre\Pathfinder\Armor;

/**
 * Combat Maneuver Defense
 */
class CmdDto
{
    public function __construct(
        readonly string $name,
        readonly int $bab,
        readonly int $strength,
        readonly int $dexterity,
        readonly int $sizeModifier
    ) {
    }

    public function total(): int
    {
        return 10 +
            $this->bab +
            $this->strength +
            $this->dexterity +
            $this->sizeModifier;
    }
}