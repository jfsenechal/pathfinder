<?php

namespace AfmLibre\Pathfinder\Ancestry;

enum SizeEnum: string
{
    case Fine = 'Fine';
    case Diminutive = 'Diminutive';
    case Tiny = 'Tiny';
    case Small = 'Small';
    case Medium = 'Medium';
    case Large = 'Large';
    case Huge = 'Juge';
    case Gargantuan = 'Gargantuan';
    case Colossal = 'Colossal';

    public static function valueModifier(SizeEnum $sizeEnum): int
    {
        return match ($sizeEnum) {
            self::Fine => -8,
            self::Diminutive => -4,
            self::Tiny => -2,
            self::Small => -1,
            self::Medium => 0,
            self::Large => 1,
            self::Huge => 2,
            self::Gargantuan => 4,
            self::Colossal => 8,
        };
    }
}
