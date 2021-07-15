<?php


namespace AfmLibre\Pathfinder\Character\Message;


class CharacterDeleted
{
    private int $characterId;

    public function __construct(int $characterId)
    {
        $this->characterId = $characterId;
    }

    public function getCharacterId(): int
    {
        return $this->characterId;
    }
}
