<?php

namespace AfmLibre\Pathfinder\Modifier;

enum ModifierListingEnum: string
{
    case ABILITY_STRENGTH = 'strength';
    case ABILITY_DEXTERITY = 'dexterity';
    case ABILITY_CONSTITUTION = 'constitution';
    case ABILITY_INTELLIGENCE = 'intelligence';
    case ABILITY_WISDOM = 'wisdom';
    case ABILITY_CHARISMA = 'charisma';

    case COMBAT_DMD = 'dmd';
    case COMBAT_BMO = 'bmo';
    case ARMOR_CLASS = 'ca';

    case SAVING_THROW_REFLEX = 'reflex';
    case SAVING_THROW_FORTITUDE = 'fortitude';//vigueur
    case SAVING_THROW_WILL = 'will';//volonte

    case SKILL = 'skill';

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
            self::SKILL->value => ModifierListingEnum::SKILL,
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
