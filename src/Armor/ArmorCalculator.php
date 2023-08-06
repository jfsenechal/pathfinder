<?php

namespace AfmLibre\Pathfinder\Armor;

use AfmLibre\Pathfinder\Ancestry\SizeEnum;
use AfmLibre\Pathfinder\Character\Repository\CharacterArmorRepository;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\CharacterArmor;

class ArmorCalculator
{
    public function __construct(private CharacterArmorRepository $characterArmorRepository)
    {
    }


    /**
     * 10 + bonus d’armure + bonus de bouclier + modificateur de Dextérité + modificateur de taille
     * @param CharacterArmor[] $characterArmors
     */
    public function createArmorAbility(
        Character $character,
        array $characterArmors,
        SizeEnum $sizeEnum
    ): ArmorClassDto {
        $dexterity = $character->dexterity;
        $caArmors = 0;
        foreach ($characterArmors as $characterArmor) {
            $armor = $characterArmor->armor;
            if ($armor->bonus_dexterity_max) {
                $dexterity = $armor->bonus_dexterity_max;
            }
            $caArmors += $armor->bonus;
        }

        return new ArmorClassDto(
            "ca",
            Character::getValueModifier($dexterity),
            $caArmors,
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
            $character->current_level->fortitude,
            Character::getValueModifier($character->dexterity),
            SizeEnum::valueModifier($sizeEnum)
        );
    }

    public function dexterityMax(Character $character): int
    {
        $dexterity = $character->dexterity;
        $characterArmors = $this->characterArmorRepository->findByCharacter($character);

        foreach ($characterArmors as $characterArmor) {
            $armor = $characterArmor->armor;
            if ($armor->bonus_dexterity_max) {
                $dexterity = $armor->bonus_dexterity_max;
            }
        }

        return $dexterity;
    }
}