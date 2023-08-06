<?php

namespace AfmLibre\Pathfinder\Leveling;

enum LevelingEnum: string
{
    case INCREASE_SKILL = 'skill';
    case INCREASE_LIFE = 'life';

    public static function choiceIncrease(): array
    {
        return [self::INCREASE_SKILL, self::INCREASE_LIFE];
    }

    public static function choicesForList(): array
    {
        return [
            self::INCREASE_SKILL->value => self::INCREASE_SKILL->value,
            self::INCREASE_LIFE->value => self::INCREASE_LIFE->value,
        ];

    }

}
