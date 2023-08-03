<?php

namespace AfmLibre\Pathfinder\Ability;

use AfmLibre\Pathfinder\Entity\Character;

class AbilityBoosts
{
    /**
     * An ability boost normally increases an ability score’s value by 2. However,
     * if the ability score to which you’re applying an ability boost is already 18 or higher,
     * its value increases by only 1. At 1st level, a character can never have any ability score that’s higher than 18.
     *
     * When your character receives an ability boost, the rules indicate whether it must be applied to a specific ability score or
     * to one of two specific ability scores, or whether it is a “free” ability boost that can be applied to any ability score of your choice.
     * However, when you gain multiple ability boosts at the same time, you must apply each one to a different score. Dwarves, for example,
     * receive an ability boost to their Constitution score and their Wisdom score,
     * as well as one free ability boost, which can be applied to any score other than Constitution or Wisdom.
     */

    public function check(Character $character) {

        if($character->current_level->lvl ===1){
            //max 18
        }

        //if ability egal 18 max give 1 point

    }
}