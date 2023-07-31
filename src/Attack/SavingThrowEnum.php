<?php

namespace AfmLibre\Pathfinder\Attack;

enum SavingThrowEnum: string
{
    case REFLEX = 'REFLEX';
    case FORTITUDE = 'FORTITUDE';//vigueur
    case WILL = 'WILL';//volonte

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
