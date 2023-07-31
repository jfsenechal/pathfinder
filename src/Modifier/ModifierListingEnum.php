<?php

namespace AfmLibre\Pathfinder\Modifier;

enum ModifierListingEnum: string
{
    case ABILITY_STRENGTH = 'STRENGTH';
    case ABILITY_DEXTERITY = 'DEXTERITY';
    case ABILITY_CONSTITUTION = 'CONSTITUTION';
    case ABILITY_INTELLIGENCE = 'INTELLIGENCE';
    case ABILITY_WISDOM = 'WISDOM';
    case ABILITY_CHARISMA = 'CHARISMA';

    case COMBAT_DMD = 'DMD';
    case COMBAT_BMO = 'BMO';
    case ARMOR_CLASS = 'CA';

    case SAVING_THROW_REFLEX = 'REFLEX';
    case SAVING_THROW_FORTITUDE = 'FORTITUDE';//vigueur
    case SAVING_THROW_WILL = 'WILL';//volonte

    public static function findByName(string $name): ?ModifierListingEnum
    {
        return match ($name) {
            self::ABILITY_STRENGTH->value => ModifierListingEnum::ABILITY_STRENGTH,
            self::ABILITY_DEXTERITY->value => ModifierListingEnum::ABILITY_DEXTERITY,
            self::ABILITY_CONSTITUTION->value => ModifierListingEnum::ABILITY_CONSTITUTION,
            self::ABILITY_INTELLIGENCE->value => ModifierListingEnum::ABILITY_INTELLIGENCE,
            self::ABILITY_WISDOM->value => ModifierListingEnum::ABILITY_WISDOM,
            self::ABILITY_CHARISMA->value => ModifierListingEnum::ABILITY_CHARISMA,
            self::COMBAT_DMD->value => ModifierListingEnum::COMBAT_DMD,
            self::ARMOR_CLASS->value => ModifierListingEnum::ARMOR_CLASS,
            self::SAVING_THROW_REFLEX->value => ModifierListingEnum::SAVING_THROW_REFLEX,
            self::SAVING_THROW_FORTITUDE->value => ModifierListingEnum::SAVING_THROW_FORTITUDE,
            self::SAVING_THROW_WILL->value => ModifierListingEnum::SAVING_THROW_WILL,
            default => null
        };
    }

    /**
     * @return ModifierListingEnum[]
     */
    public static function abilities(): array
    {
        return [
            self::ABILITY_STRENGTH,
            self::ABILITY_DEXTERITY,
            self::ABILITY_CONSTITUTION,
            self::ABILITY_INTELLIGENCE,
            self::ABILITY_WISDOM,
        ];
    }

    /**
     * @return ModifierListingEnum[]
     */
    public static function savingThrows(): array
    {
        return [
            self::SAVING_THROW_FORTITUDE,
            self::SAVING_THROW_REFLEX,
            self::SAVING_THROW_WILL,
        ];
    }

}
