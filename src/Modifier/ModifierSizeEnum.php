<?php

namespace AfmLibre\Pathfinder\Modifier;

enum ModifierSizeEnum
{
    case SIZE_FINE;
    case SIZE_DIMINUTIVE;
    case SIZE_TINY;
    case SIZE_SMALL;
    case SIZE_LARGE_TALL;
    case SIZE_LARGE_LONG;
    case SIZE_HUGE_TALL;
    case SIZE_HUGE_LONG;
    case SIZE_GARG_TALL;
    case SIZE_GARG_LONG;
    case SIZE_COLO_TALL;
    case SIZE_COLO_LONG;

    public function getModificateur(): int
    {
        return match ($this) {
            self::SIZE_FINE => 8,
            self::SIZE_DIMINUTIVE => 4,
            self::SIZE_TINY => 2,
            self::SIZE_SMALL => 1,
            self::SIZE_LARGE_TALL => -1,
            self::SIZE_LARGE_LONG => -1,
            self::SIZE_HUGE_TALL => -2,
            self::SIZE_HUGE_LONG => -2,
            self::SIZE_GARG_TALL => -4,
            self::SIZE_GARG_LONG => -4,
            self::SIZE_COLO_TALL => -8,
            self::SIZE_COLO_LONG => -8,
        };
    }

}
