<?php

namespace AfmLibre\Pathfinder\Modifier;

enum ModifierEnum: string
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

    public static function findByName(string $name): ?ModifierEnum
    {
        return match ($name) {
            self::ABILITY_STRENGTH->value => ModifierEnum::ABILITY_STRENGTH,
            self::ABILITY_DEXTERITY->value => ModifierEnum::ABILITY_DEXTERITY,
            self::ABILITY_CONSTITUTION->value => ModifierEnum::ABILITY_CONSTITUTION,
            self::ABILITY_INTELLIGENCE->value => ModifierEnum::ABILITY_INTELLIGENCE,
            self::ABILITY_WISDOM->value => ModifierEnum::ABILITY_WISDOM,
            self::ABILITY_CHARISMA->value => ModifierEnum::ABILITY_CHARISMA,
            self::COMBAT_DMD->value => ModifierEnum::COMBAT_DMD,
            self::ARMOR_CLASS->value => ModifierEnum::ARMOR_CLASS,
            self::SAVING_THROW_REFLEX->value => ModifierEnum::SAVING_THROW_REFLEX,
            self::SAVING_THROW_FORTITUDE->value => ModifierEnum::SAVING_THROW_FORTITUDE,
            self::SAVING_THROW_WILL->value => ModifierEnum::SAVING_THROW_WILL,
            default => null
        };
    }

    /**
     * @return ModifierEnum[]
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
     * @return ModifierEnum[]
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
