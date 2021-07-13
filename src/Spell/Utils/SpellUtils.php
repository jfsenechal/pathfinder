<?php


namespace AfmLibre\Pathfinder\Spell\Utils;


use AfmLibre\Pathfinder\Entity\CharacterSpell;
use AfmLibre\Pathfinder\Entity\Spell;

class SpellUtils
{
    /**
     * @param array|CharacterSpell[] $characterSpells
     * @return array|Spell[]
     */
    public static function groupByLevel(array $characterSpells): array
    {
        $spells = [];
        foreach ($characterSpells as $characterSpell) {
            $spells[$characterSpell->getLevel()][] = $characterSpell->getSpell();
        }

        return $spells;
    }
}
