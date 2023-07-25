<?php


namespace AfmLibre\Pathfinder\Level;

class LevelUtils
{
    /**
     * @return array|int[]
     */
    public static function getSpellLevels(): array
    {
        return range(0, 9);
    }
}
