<?php


namespace AfmLibre\Pathfinder\Spell\Utils;

use AfmLibre\Pathfinder\Entity\FavoriteSpell;
use AfmLibre\Pathfinder\Entity\Spell;

class SpellUtils
{
    /**
     * @param FavoriteSpell[] $characterSpells
     * @return Spell[]
     */
    public static function groupByLevel(iterable $characterSpells): array
    {
        $data = [];
        foreach ($characterSpells as $characterSpell) {
            $data[$characterSpell->level][] = $characterSpell->spell;
        }

        ksort($data);

        return $data;
    }
}
