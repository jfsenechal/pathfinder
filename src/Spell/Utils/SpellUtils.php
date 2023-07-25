<?php


namespace AfmLibre\Pathfinder\Spell\Utils;


use AfmLibre\Pathfinder\Entity\CharacterSpell;

class SpellUtils
{
    /**
     * @param iterable|CharacterSpell[] $characterSpells
     * @return array|CharacterSpell[]
     */
    public static function groupByLevel(iterable $characterSpells): array
    {
        $data = [];
        foreach ($characterSpells as $characterSpell) {
            $data[$characterSpell->level][] = $characterSpell;
        }

        ksort($data);

        return $data;
    }
}
