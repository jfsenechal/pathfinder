<?php

namespace AfmLibre\Pathfinder\Armor;

use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Modifier\ModifierSizeEnum;

class ArmorCalculator
{

    /**
     * 10 + bonus d’armure + bonus de bouclier + modificateur de Dextérité + modificateur de taille
     */
    public static function createArmorAbility(
        Character $character,
        array $characterArmors,
        ModifierSizeEnum $modifier
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
            $modifier->getModificateur()
        );
    }


    /**
     * 10 + bonus de base à l’attaque + modificateur de Force + modificateur de Dextérité + modificateur de taille spécial
     */
    public static function createDmd(Character $character, ModifierSizeEnum $modifier): DmdDto
    {
        return new DmdDto(
            "dmd",
            $character->current_level->bab,
            $character->current_level->fortitude,
            Character::getValueModifier($character->dexterity),
            $modifier->getModificateur()
        );
    }
}