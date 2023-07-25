<?php

namespace AfmLibre\Pathfinder\Ability;

class AbilityDto
{
    public function __construct(readonly string $name, readonly int $base, readonly int $caracteristic)
    {
    }

    public function getTotal(): int
    {
        return $this->base + $this->caracteristic;
    }
}