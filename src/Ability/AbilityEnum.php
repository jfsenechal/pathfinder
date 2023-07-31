<?php


namespace AfmLibre\Pathfinder\Ability;

enum AbilityEnum: string
{
    case ABILITY_STRENGH = 'STRENGTH';
    case ABILITY_DEXTERITY = 'DEXTERITY';
    case ABILITY_CONSTITUTION = 'CONSTITUTION';
    case ABILITY_INTELLIGENCE = 'INTELLIGENCE';
    case ABILITY_WISDOM = 'WISDOM';
    case ABILITY_CHARISMA = 'CHARISMA';

    public static function valueModifier(int $val): int
    {
        return match ($val) {
            1 => -5,
            2, 3 => -4,
            4, 5 => -3,
            6, 7 => -2,
            8, 9 => -1,
            10, 11 => 0,
            12, 13 => +1,
            14, 15 => +2,
            16, 17 => +3,
            18, 19 => +4,
            21, 20 => +5,
            22, 23 => +6,
        };
    }
}
