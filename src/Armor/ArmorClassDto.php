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
        readonly int $dexterityModifier,
        readonly ?int $dexterityMax,
        readonly int $armorBonus,
        readonly int $sizeModifier,
        readonly array $items = []
    ) {
    }

    public function ac(): int
    {
        $dexterityModifier = $this->dexterityModifier;
        if ($this->dexterityMax !== null && $this->dexterityMax < $this->dexterityModifier) {
            $dexterityModifier = $this->dexterityMax;
        }

        return 10 + $this->armorBonus + $dexterityModifier;
    }
}