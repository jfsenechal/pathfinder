<?php


namespace AfmLibre\Pathfinder\Spell\Dto;


class QuantityDto
{
    private int $quantity = 1;
    private int $spellId;

    public function __construct(int $spellId)
    {
        $this->spellId = $spellId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): Quantity
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getSpellId(): int
    {
        return $this->spellId;
    }

    public function setSpellId(int $spellId): Quantity
    {
        $this->spellId = $spellId;

        return $this;
    }

}
