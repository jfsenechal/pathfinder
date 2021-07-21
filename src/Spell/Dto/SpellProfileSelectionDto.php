<?php


namespace AfmLibre\Pathfinder\Spell\Dto;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class SpellProfileSelectionDto
{
    private iterable $quantities;
    private iterable $spells;

    public function __construct()
    {
        $this->quantities = new ArrayCollection();
        $this->spells = [];
    }

    public function getQuantities(): Collection
    {
        return $this->quantities;
    }

    public function getSpells(): iterable
    {
        return $this->spells;
    }

    public function setSpells(iterable $spells): SpellProfileSelectionDto
    {
        $this->spells = $spells;

        return $this;
    }
}
