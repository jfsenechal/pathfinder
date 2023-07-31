<?php

namespace AfmLibre\Pathfinder\Modifier;

class ModifierCalculator
{

    public static function abilityValueModifier(int $val): int
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