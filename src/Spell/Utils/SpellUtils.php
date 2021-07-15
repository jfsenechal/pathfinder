<?php


namespace AfmLibre\Pathfinder\Spell\Utils;


use AfmLibre\Pathfinder\Entity\CharacterSpell;

class SpellUtils
{
    /**
     * @param array|CharacterSpell[] $characterSpells
     * @return array|CharacterSpell[]
     */
    public static function groupByLevel(array $characterSpells): array
    {
        $data = [];
        foreach ($characterSpells as $characterSpell) {
            $data[$characterSpell->getLevel()][] = $characterSpell;
        }

         ksort($data);

        return $data;
    }
}
