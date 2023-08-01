<?php

namespace AfmLibre\Pathfinder\Armor;

/**
 * Armor Class = 10 + Dexterity modifier (up to your armor’s Dex Cap)
 * + proficiency bonus + armor’s item bonus to AC + other bonuses + penalties
 */
class ArmorClassDto
{
    public function __construct(
        readonly string $name,
        readonly int $dexterity,
        readonly int $armorsCa,
        readonly int $sizeModifier
    ) {
    }

    public function total(): int
    {
        return 10 + $this->armorsCa + $this->dexterity;
    }
}