<?php

namespace AfmLibre\Pathfinder\Armor;

use AfmLibre\Pathfinder\Modifiers\ModifierInterface;

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
        readonly int $shieldBonus,
        readonly int $sizeModifier,
        /**
         * @var ModifierInterface[]
         */
        readonly array $items = []
    ) {
    }

    public function ac(): int
    {
        $dexterityModifier = $this->dexterityModifier;
        if ($this->dexterityMax !== null && $this->dexterityMax < $this->dexterityModifier) {
            $dexterityModifier = $this->dexterityMax;
        }
        $bonusItems = 0;
        foreach ($this->items as $item) {
            $bonusItems += $item->valueModifier();
        }

        return 10 + $this->armorBonus + $dexterityModifier + $bonusItems;
    }
}