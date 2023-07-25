<?php


namespace AfmLibre\Pathfinder\Ability;


enum AbilityEnum
{
    final   public const   ABILITY_STRENGH = 0;
    final   public const   ABILITY_DEXTERITY = 1;
    final   public const   ABILITY_CONSTITUTION = 2;
    final   public const   ABILITY_INTELLIGENCE = 3;
    final   public const   ABILITY_WISDOM = 4;
    final   public const   ABILITY_CHARISMA = 5;

    public static function getValueModifier(int $val): int
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
        };
    }

}
