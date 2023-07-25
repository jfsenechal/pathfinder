<?php


namespace AfmLibre\Pathfinder\Spell\Utils;


use AfmLibre\Pathfinder\Entity\CharacterSpell;
use AfmLibre\Pathfinder\Entity\Spell;

class SpellUtils
{
    /**
     * @param iterable|CharacterSpell[] $characterSpells
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
