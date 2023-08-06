<?php

namespace AfmLibre\Pathfinder\Armor;

/**
 * Combat Maneuver Defense
 * bab => Base Attack Bonus
 */
class CmdDto
{
    public function __construct(
        readonly string $name,
        readonly int $bab,
        readonly int $strengthModifier,
        readonly int $dexterityModifier,
        readonly int $sizeModifier
    ) {
    }

    public function total(): int
    {
        return 10 +
            $this->bab +
            $this->strengthModifier +
            $this->dexterityModifier +
            $this->sizeModifier;
    }
}