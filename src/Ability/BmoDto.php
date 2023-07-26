<?php

namespace AfmLibre\Pathfinder\Ability;

class BmoDto
{
    public function __construct(
        readonly string $name,
        readonly int $bab,
        readonly int $fortitude,
        readonly int $sizeModifier
    ) {
    }

    public function total(): int
    {
        return $this->bab + $this->fortitude + $this->sizeModifier;
    }
}