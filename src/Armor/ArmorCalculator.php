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
        SizeEnum $sizeEnum
    ): ArmorClassDto {
        $armor = $character->armor;
        $dexterityMax = null;
        $armorBonus = 0;
        if ($armor && $armor->bonus_dexterity_max) {
            $dexterityMax = $armor->bonus_dexterity_max;
            $armorBonus = $armor->bonus;
        }

        return new ArmorClassDto(
            "ca",
            Character::getValueModifier($character->dexterity),
            $dexterityMax,
            $armorBonus,
            SizeEnum::valueModifier($sizeEnum)
        );
    }

    /**
     * 10 + bonus de base à l’attaque + modificateur de Force + modificateur de Dextérité + modificateur de taille spécial
     */
    public function createCmd(Character $character, SizeEnum $sizeEnum): CmdDto
    {
        return new CmdDto(
            "cmd",
            $character->current_level->bab,
            Character::getValueModifier($character->strength),
            Character::getValueModifier($character->dexterity),
            SizeEnum::valueModifier($sizeEnum)
        );
    }

    public function dexterityMax(Character $character): int
    {
        $dexterity = $character->dexterity;
        $armor = $character->armor;
        if ($armor->bonus_dexterity_max) {
            $dexterity = $armor->bonus_dexterity_max;
        }

        return $dexterity;
    }
}