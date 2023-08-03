<?php


namespace AfmLibre\Pathfinder\Spell\Message;

class FavoriteSpellUpdated
{
    public function __construct(private readonly ?int $spellId = null)
    {
    }

    public function getSpellId(): int
    {
        return $this->spellId;
    }
}
