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

    public static function returnByNameFr(string $nameFr): ?self
    {
        return match ($nameFr) {
            'Charisme' => self::ABILITY_CHARISMA,
            'Dextérité' => self::ABILITY_DEXTERITY,
            'Force' => self::ABILITY_STRENGH,
            'Intelligence' => self::ABILITY_INTELLIGENCE,
            'Sagesse' => self::ABILITY_WISDOM,
            default => null,
        };

    }
}
