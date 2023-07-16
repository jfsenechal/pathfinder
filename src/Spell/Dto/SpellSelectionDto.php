<?php


namespace AfmLibre\Pathfinder\Spell\Dto;


use AfmLibre\Pathfinder\Entity\Character;
use AfmLibre\Pathfinder\Entity\Spell;

class SpellSelectionDto
{
    public function __construct(private Character $character_player, private array $spells)
    {
    }

    public function getCharacterPlayer(): Character
    {
        return $this->character_player;
    }

    public function setCharacterPlayer(Character $character_player): void
    {
        $this->character_player = $character_player;
    }

    /**
     * @return array|Spell[]
     */
    public function getSpells(): array
    {
        return $this->spells;
    }

    /**
     * @param array|Spell[] $spells
     */
    public function setSpells(array $spells): void
    {
        $this->spells = $spells;
    }

}
