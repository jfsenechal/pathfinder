<?php

namespace AfmLibre\Pathfinder\Leveling;

enum LevelingEnum: string
{
    case INCREASE_SKILL = 'SKILL';
    case INCREASE_LIFE = 'LIFE';

    public static function choiceIncrease(): array
    {
        return [self::INCREASE_SKILL, self::INCREASE_LIFE];
    }

}
