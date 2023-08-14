<?php


namespace AfmLibre\Pathfinder\Ability;

enum AbilityEnum: string
{
    case ABILITY_STRENGH = 'strength';
    case ABILITY_DEXTERITY = 'dexterity';
    case ABILITY_CONSTITUTION = 'constitution';
    case ABILITY_INTELLIGENCE = 'intelligence';
    case ABILITY_WISDOM = 'wisdom';
    case ABILITY_CHARISMA = 'charisma';

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
