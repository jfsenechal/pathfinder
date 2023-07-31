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
    case ABILITY_DMD = 'DMD';
    case ABILITY_CA = 'CA';
    case ABILITY_REFLEX = 'REFLEX';
    case ABILITY_FORTITUDE = 'FORTITUDE';//vigueur
    case ABILITY_WILL = 'WILL';//volonte

    public static function findByName(string $name): ?ModifierEnum
    {
        return match ($name) {
            self::ABILITY_STRENGTH->value => ModifierEnum::ABILITY_STRENGTH,
            self::ABILITY_DEXTERITY->value => ModifierEnum::ABILITY_DEXTERITY,
            self::ABILITY_CONSTITUTION->value => ModifierEnum::ABILITY_CONSTITUTION,
            self::ABILITY_INTELLIGENCE->value => ModifierEnum::ABILITY_INTELLIGENCE,
            self::ABILITY_WISDOM->value => ModifierEnum::ABILITY_WISDOM,
            self::ABILITY_CHARISMA->value => ModifierEnum::ABILITY_CHARISMA,
            self::ABILITY_DMD->value => ModifierEnum::ABILITY_DMD,
            self::ABILITY_CA->value => ModifierEnum::ABILITY_CA,
            self::ABILITY_REFLEX->value => ModifierEnum::ABILITY_REFLEX,
            self::ABILITY_FORTITUDE->value => ModifierEnum::ABILITY_FORTITUDE,
            self::ABILITY_WILL->value => ModifierEnum::ABILITY_WILL,
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

}
