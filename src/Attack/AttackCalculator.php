<?php

namespace AfmLibre\Pathfinder\Attack;

use AfmLibre\Pathfinder\Ancestry\SizeEnum;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\Weapon;

class AttackCalculator
{
    //BMO=Bonus de base à l’attaque + modificateur de Force + modificateur de taille spécial
    //Le bonus de manœuvre offensive
    public static function createCmb(Character $character, SizeEnum $modifier): CmbDto
    {
        return
            new CmbDto(
                "cmb",
                $character->current_level->bab,
                $character->current_level->fortitude,
                $modifier->getModificateur()
            );
    }

    public static function createAttackRoll(
        Character $character,
        Weapon $weapon,
        SizeEnum $modifier
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