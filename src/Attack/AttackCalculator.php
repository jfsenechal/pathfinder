<?php

namespace AfmLibre\Pathfinder\Attack;

use AfmLibre\Pathfinder\Ancestry\SizeEnum;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\Weapon;

class AttackCalculator
{
    //BMO=Bonus de base à l’attaque + modificateur de Force + modificateur de taille spécial
    //Le bonus de manœuvre offensive
    public function createCmb(Character $character, SizeEnum $sizeEnum): CmbDto
    {
        return
            new CmbDto(
                "cmb",
                $character->current_level->bab,
                Character::getValueModifier($character->strength),
                SizeEnum::valueModifier($sizeEnum)
            );
    }

    public static function createAttackRoll(
        Character $character,
        Weapon $weapon,
        SizeEnum $sizeEnum
    ): AttackRoll {
        $characteristicName = 'strength';

        $characteristic = $character->strength;
        $rangePenalty = 0;

        if ($weapon->distance) {
            $characteristicName = 'dexterity';
            $characteristic = $character->dexterity;
            $rangePenalty = $weapon->ranged;
        }

        return new AttackRoll(
            "ja",
            $character->current_level->bab,
            $characteristicName,
            Character::getValueModifier($characteristic),
            SizeEnum::valueModifier($sizeEnum),
            $rangePenalty
        );
    }

    public static function createDamageRoll(
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
