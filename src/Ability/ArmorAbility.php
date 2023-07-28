<?php

namespace AfmLibre\Pathfinder\Ability;

class ArmorAbility
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