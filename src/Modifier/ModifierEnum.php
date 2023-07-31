<?php

namespace AfmLibre\Pathfinder\Modifier;

enum ModifierEnum: string
{
    case ABILITY_STRENGH = 'STRENGH';
    case ABILITY_DEXTERITY = 'DEXTERITY';
    case ABILITY_CONSTITUTION = 'CONSTITUTION';
    case ABILITY_INTELLIGENCE = 'INTELLIGENCE';
    case ABILITY_WISDOM = 'WISDOM';
    case ABILITY_CHARISMA = 'CHARISMA';
    case ABILITY_DMD = 'DMD';
    case ABILITY_CA = 'CA';

    public static function findByName(string $name)
    {
        return match ($name) {
            self::ABILITY_STRENGH->value => ModifierEnum::ABILITY_STRENGH,
            self::ABILITY_DEXTERITY->value => ModifierEnum::ABILITY_DEXTERITY,
            self::ABILITY_CONSTITUTION->value => ModifierEnum::ABILITY_CONSTITUTION,
            self::ABILITY_INTELLIGENCE->value => ModifierEnum::ABILITY_INTELLIGENCE,
            self::ABILITY_WISDOM->value => ModifierEnum::ABILITY_WISDOM,
            self::ABILITY_CHARISMA->value => ModifierEnum::ABILITY_CHARISMA,
            self::ABILITY_DMD->value => ModifierEnum::ABILITY_DMD,
            self::ABILITY_CA->value => ModifierEnum::ABILITY_CA,
            default => null
        };
    }

}
