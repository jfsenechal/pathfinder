<?php


namespace AfmLibre\Pathfinder\Level\Message;

class LevelCrossingUpdated
{
    public function __construct(private readonly int $characterId)
    {
    }

    public function getCharacterId(): int
    {
        return $this->characterId;
    }
}
