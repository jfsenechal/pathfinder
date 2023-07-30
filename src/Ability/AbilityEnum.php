<?php


namespace AfmLibre\Pathfinder\Ability;

enum AbilityEnum: int
{
    case ABILITY_STRENGH = 0;
    case ABILITY_DEXTERITY = 1;
    case ABILITY_CONSTITUTION = 2;
    case ABILITY_INTELLIGENCE = 3;
    case ABILITY_WISDOM = 4;
    case ABILITY_CHARISMA = 5;

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
