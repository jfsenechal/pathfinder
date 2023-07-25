<?php


namespace AfmLibre\Pathfinder\Spell\Dto;

class QuantityDto
{
    private int $quantity = 1;

    public function __construct(private int $spellId)
    {
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): QuantityDto
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getSpellId(): int
    {
        return $this->spellId;
    }

    public function setSpellId(int $spellId): QuantityDto
    {
        $this->spellId = $spellId;

        return $this;
    }
}
