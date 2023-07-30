<?php

namespace AfmLibre\Pathfinder\Attack;

use AfmLibre\Pathfinder\Ability\BmoDto;
use AfmLibre\Pathfinder\Ability\DmdDto;
use AfmLibre\Pathfinder\Armor\ArmorClass;
use AfmLibre\Pathfinder\Damage\DamageRoll;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterArmor;
use AfmLibre\Pathfinder\Entity\Weapon;
use AfmLibre\Pathfinder\Modifier\ModifierSizeEnum;

class AttackCalculator
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
     * @param CharacterArmor[] $characterArmors
     * @param ModifierSizeEnum $modifier
     */
    public static function createArmorAbility(
        Character $character,
        array $characterArmors,
        ModifierSizeEnum $modifier
    ): ArmorClass {
        $caArmors = 0;
        foreach ($characterArmors as $characterArmor) {
            $armor = $characterArmor->armor;
            $caArmors += $armor->bonus;
        }

        return new ArmorClass(
            "ca",
            Character::getValueModifier($character->dexterity),
            $caArmors,
            $modifier->getModificateur()
        );
    }

    public static function createAttackRoll(
        Character $character,
        Weapon $weapon,
        ModifierSizeEnum $modifier
    ): AttackRoll {

        $caracteristic = $character->strength;
        $rangePenalty = 0;

        if ($weapon->distance) {
            $caracteristic = $character->dexterity;
            $rangePenalty = $weapon->ranged;
        }

        return new AttackRoll(
            "ja",
            $character->current_level->bab,
            Character::getValueModifier($caracteristic),
            $modifier->getModificateur(),
            $rangePenalty
        );
    }

    public static function createDamageAbility(
        Character $character,
        Weapon $weapon,
    ): DamageRoll {

        return new DamageRoll(
            "jd",
            $character->current_level->bab,
            $weapon->damageMedium,
            Character::getValueModifier($character->strength),
            true,
            true
        );
    }
}