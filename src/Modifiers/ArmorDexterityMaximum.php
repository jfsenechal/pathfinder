<?php

namespace AfmLibre\Pathfinder\Modifiers;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Modifier\ModifierListingEnum;

class ArmorDexterityMaximum implements ModifierInterface
{
    private Character $character;

    public function isCharacterConcern(Character $character): bool
    {
        $this->character = $character;

        return $this->character->armor === !null;
    }

    public function useOn(): ModifierListingEnum
    {
        return ModifierListingEnum::ABILITY_DEXTERITY;
    }

    public function valueModifier(): int
    {
        return $this->character->armor?->bonus_dexterity_max;
    }

    public function explain(): string
    {
       return 'Armor maximum dexterity';
    }
}