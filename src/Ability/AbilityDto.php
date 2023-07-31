<?php

namespace AfmLibre\Pathfinder\Ability;

use AfmLibre\Pathfinder\Entity\Modifier;

class AbilityDto
{
    /**
     * @param string $name
     * @param int $base
     * @param string $caracteristicName
     * @param int $caracteristicValue
     * @param Modifier[] $modifiers
     */
    public function __construct(
        readonly string $name,
        readonly int $base,
        readonly string $caracteristicName,
        readonly int $caracteristicValue,
        readonly array $modifiers
    ) {
    }

    public function total(): int
    {
        return $this->base + $this->caracteristicValue;
    }
}