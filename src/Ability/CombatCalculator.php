<?php

namespace AfmLibre\Pathfinder\Ability;

use AfmLibre\Pathfinder\Entity\Armor;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\Weapon;
use AfmLibre\Pathfinder\Modifier\ModifierSizeEnum;

class CombatCalculator
{
//BMO=Bonus de base à l’attaque + modificateur de Force + modificateur de taille spécial
    //Le bonus de manœuvre offensive
    public static function createBmo(Character $character, ModifierSizeEnum $modifier): BmoDto
    {
        return
            new BmoDto(
                "bmo",
                $character->current_level->bab,
                $character->current_level->fortitude,
                $modifier->getModificateur()
            );
    }

    /**
     *
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

    /**
     * 10 + bonus d’armure + bonus de bouclier + modificateur de Dextérité + modificateur de taille
     * @param Character $character
     * @param Armor[] $armors
     * @param ModifierSizeEnum $modifier
     * @return ArmorAbility
     */
    public static function createArmorAbility(
        Character $character,
        array $armors,
        ModifierSizeEnum $modifier
    ): ArmorAbility {
        $caArmors = 0;
        foreach ($armors as $armor) {
            $caArmors += $armor->bonus;
        }

        return new ArmorAbility(
            "ca",
            Character::getValueModifier($character->dexterity),
            $caArmors,
            $modifier->getModificateur()
        );
    }

    public static function createAttackAbility(
        Character $character,
        Weapon $weapon,
        ModifierSizeEnum $modifier
    ): AttackAbility {

        $caracteristic = $character->strength;

        if ($weapon->distance) {
            $caracteristic = $character->dexterity;
        }

        return new AttackAbility(
            "ja",
            $character->current_level->bab,
            Character::getValueModifier($caracteristic),
            $weapon->damageMedium,
            $modifier->getModificateur()
        );
    }

    public static function createDamageAbility(
        Character $character,
        Weapon $weapon,
    ): DamageAbility {

        return new DamageAbility(
            "jd",
            $character->current_level->bab,
            $weapon->damageMedium,
            Character::getValueModifier($character->strength),
            true,
            true
        );
    }
}