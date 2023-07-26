<?php

namespace AfmLibre\Pathfinder\Ability;

class DmdDto
{
    public function __construct(
        readonly string $name,
        readonly int $bab,
        readonly int $fortitude,
        readonly int $dexterity,
        readonly int $sizeModifier
    ) {
    }

    public function total(): int
    {
        return 10 +
            $this->bab +
            $this->fortitude +
            $this->dexterity +
            $this->sizeModifier;
    }
}