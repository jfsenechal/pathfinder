<?php


namespace AfmLibre\Pathfinder\Spell\Message;


class SpellAvailableUpdated
{
    private ?int $spellId;

    public function __construct(?int $spellId = null)
    {
        $this->spellId = $spellId;
    }

    public function getSpellId(): int
    {
        return $this->spellId;
    }
}
