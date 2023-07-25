<?php


namespace AfmLibre\Pathfinder\Character\Message;

class CharacterUpdated
{
    public function __construct(private readonly int $characterId)
    {
    }

    public function getCharacterId(): int
    {
        return $this->characterId;
    }
}
