<?php

namespace AfmLibre\Pathfinder\Armor;

use AfmLibre\Pathfinder\Ancestry\SizeEnum;
use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Modifier\ModifierListingEnum;
use AfmLibre\Pathfinder\Modifiers\ModifierInterface;
use AfmLibre\Pathfinder\Modifiers\ModifiersHandler;

class ArmorCalculator
{
    public function __construct(private readonly ModifiersHandler $modifiersHandler)
    {
    }

    /**
     * @param Character $character
     * @return ModifierInterface[]
     */
    public function items(Character $character): array
    {
        $modifiers = $this->modifiersHandler->locate(ModifierListingEnum::ARMOR_CLASS);
        $items = [];
        foreach ($modifiers as $modifier) {
            if ($modifier->isCharacterConcern($character)) {
                $items[] = $modifier;
            }
        }

        return $items;
    }

    /**
     * 10 + bonus d’armure + bonus de bouclier + modificateur de Dextérité + modificateur de taille
     */
    public function createArmorAbility(
        Character $character,
        SizeEnum $sizeEnum
    ): ArmorClassDto {

        $dexterityMax = self::dexterityMax($character);
        $armorBonus = $character->armor?->bonus ?? 0;
        $shieldBonus = $character->shield?->bonus ?? 0;
        $items = $this->items($character);

        return new ArmorClassDto(
            "ca",
            Character::getValueModifier($character->dexterity),
            $dexterityMax,
            $armorBonus,
            $shieldBonus,
            SizeEnum::valueModifier($sizeEnum),
            $items
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

    private static function dexterityMax(Character $character): ?int
    {
        $armor = $character->armor;
        if (!$armor) {
            return null;
        }
        if ($armor->bonus_dexterity_max === null) {
            return null;
        }
        if ((int)$armor->bonus_dexterity_max >= 0) {
            return (int)$armor->bonus_dexterity_max;
        }

        return null;
    }
}