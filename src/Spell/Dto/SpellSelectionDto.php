<?php


namespace AfmLibre\Pathfinder\Spell\Dto;


use AfmLibre\Pathfinder\Entity\Character;

class SpellSelectionDto
{
    private Character $character_player;
    private array $spells;

    public function __construct(Character $character, array $spells)
    {
        $this->character_player = $character;
        $this->spells = $spells;
    }

    /**
     * @return \AfmLibre\Pathfinder\Entity\Character
     */
    public function getCharacterPlayer(): Character
    {
        return $this->character_player;
    }

    /**
     * @param \AfmLibre\Pathfinder\Entity\Character $character_player
     */
    public function setCharacterPlayer(Character $character_player): void
    {
        $this->character_player = $character_player;
    }

    /**
     * @return array
     */
    public function getSpells(): array
    {
        return $this->spells;
    }

    /**
     * @param array $spells
     */
    public function setSpells(array $spells): void
    {
        $this->spells = $spells;
    }

}
