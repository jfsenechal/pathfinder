<?php

namespace AfmLibre\Pathfinder\Ability;

class CaDto
{
    public function __construct(
        readonly string $name,
        readonly int $dexterity,
        readonly int $armorsCa,
        readonly int $sizeModifier
    ) {
    }

    public function total(): int
    {
        return 10+ $this->armorsCa + $this->dexterity;
    }
}