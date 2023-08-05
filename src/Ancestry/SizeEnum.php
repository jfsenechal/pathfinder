<?php

namespace AfmLibre\Pathfinder\Ancestry;

enum SizeEnum
{
    case SIZE_FINE;
    case SIZE_DIMINUTIVE;
    case SIZE_TINY;
    case SIZE_SMALL;
    case SIZE_MIDDLE;
    case SIZE_LARGE_TALL;
    case SIZE_LARGE_LONG;
    case SIZE_HUGE_TALL;
    case SIZE_HUGE_LONG;
    case SIZE_GARG_TALL;
    case SIZE_GARG_LONG;
    case SIZE_COLO_TALL;
    case SIZE_COLO_LONG;

    /**
     * Colossal    +8
     * Gargantuan    +4
     * Huge    +2
     * Large    +1
     * Medium    +0
     * Small    -1
     * Tiny    -2
     * Diminutive    -4
     * Fine    -8
     * @return int
     */
    public function getModificateur(): int
    {
        return match ($this) {
            self::SIZE_FINE => -8,
            self::SIZE_DIMINUTIVE => -4,
            self::SIZE_TINY => -2,
            self::SIZE_SMALL => -1,
            self::SIZE_MIDDLE => 0,
            self::SIZE_LARGE_TALL, self::SIZE_LARGE_LONG => +1,
            self::SIZE_HUGE_TALL, self::SIZE_HUGE_LONG => +2,
            self::SIZE_GARG_TALL, self::SIZE_GARG_LONG => +4,
            self::SIZE_COLO_TALL, self::SIZE_COLO_LONG => +8,
        };
    }
}
