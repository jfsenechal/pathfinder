<?php

namespace AfmLibre\Pathfinder\SavingThrow;

use AfmLibre\Pathfinder\Entity\Modifier;

class SavingThrowDto
{
    /**
     * @param Modifier[] $modifiers
     */
    public function __construct(
        readonly string $name,
        readonly int $base,
        readonly string $caracteristicName,
        readonly int $caracteristicValue,
        readonly array $modifiers = []
    ) {
    }

    public function total(): int
    {
        return $this->base + $this->caracteristicValue;
    }
}
