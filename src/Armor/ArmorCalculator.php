<?php

namespace AfmLibre\Pathfinder\Armor;

use AfmLibre\Pathfinder\Ancestry\SizeEnum;
use AfmLibre\Pathfinder\Entity\Character;

class ArmorCalculator
{

    /**
     * 10 + bonus d’armure + bonus de bouclier + modificateur de Dextérité + modificateur de taille
     */
    public static function createArmorAbility(
        Character $character,
        array $characterArmors,
        SizeEnum $sizeEnum
    ): ArmorClassDto {
        $caArmors = 0;
        foreach ($characterArmors as $characterArmor) {
            $armor = $characterArmor->armor;
            $caArmors += $armor->bonus;
        }

        return new ArmorClassDto(
            "ca",
            Character::getValueModifier($character->dexterity),
            $caArmors,
            SizeEnum::valueModifier($sizeEnum)
        );
    }


    /**
     * 10 + bonus de base à l’attaque + modificateur de Force + modificateur de Dextérité + modificateur de taille spécial
     */
    public static function createCmd(Character $character, SizeEnum $sizeEnum): CmdDto
    {
        return new CmdDto(
            "cmd",
            $character->current_level->bab,
            $character->current_level->fortitude,
            Character::getValueModifier($character->dexterity),
            SizeEnum::valueModifier($sizeEnum)
        );
    }
}