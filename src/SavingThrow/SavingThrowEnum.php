<?php

namespace AfmLibre\Pathfinder\SavingThrow;

use AfmLibre\Pathfinder\Modifier\ModifierListingEnum;

enum SavingThrowEnum: string
{
    case Reflex = 'reflex';
    case Fortitude = 'fortitude';//vigueur
    case Will = 'will';//volonte

    public static function abilityLinked(self $st): string
    {
        return match ($st) {
            self::Reflex => ModifierListingEnum::ABILITY_DEXTERITY->value,
            self::Fortitude => ModifierListingEnum::ABILITY_CONSTITUTION->value,
            self::Will => ModifierListingEnum::ABILITY_WISDOM->value,
        };
    }

    /**
     * Fortitude save result = d20 roll + Constitution modifier + proficiency bonus + other bonuses + penalties
     *
     * Reflex saving throws measure how well you can respond quickly to a situation and how gracefully you can avoid effects that have been thrown at you. They use your Dexterity modifier and are calculated as shown in the formula below.
     * Reflex save result = d20 roll + Dexterity modifier + proficiency bonus + other bonuses + penalties
     *
     * Will saving throws measure how well you can resist attacks to your mind and spirit. They use your Wisdom modifier and are calculated as shown in the formula below.
     * Will save result = d20 roll + Wisdom modifier + proficiency bonus + other bonuses + penalties
     */
}
