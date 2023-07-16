<?php


namespace AfmLibre\Pathfinder\Spell\Dto;

use AfmLibre\Pathfinder\Entity\CharacterSpell;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class SpellProfileSelectionDto
{
    private readonly iterable $quantities;

    public function __construct(private iterable $spells, private iterable $spellsAvailable)
    {
        $this->quantities = new ArrayCollection();
        $this->initQuantities();
    }

    private function initQuantities()
    {
        foreach ($this->spellsAvailable as $characterSpell) {
            $quantityDto = new QuantityDto($characterSpell->getId());
            $this->getQuantities()->add($quantityDto);
        }
    }

    public function getQuantities(): Collection
    {
        return $this->quantities;
    }

    /**
     * @return iterable|CharacterSpell[]
     */
    public function getSpells(): iterable
    {
        return $this->spells;
    }

    public function setSpells(iterable $spells): SpellProfileSelectionDto
    {
        $this->spells = $spells;

        return $this;
    }

    /**
     * @return iterable|CharacterSpell[]
     */
    public function getSpellsAvailable()
    {
        return $this->spellsAvailable;
    }

    /**
     * @param array|iterable $spellsAvailable
     * @return SpellProfileSelectionDto
     */
    public function setSpellsAvailable($spellsAvailable)
    {
        $this->spellsAvailable = $spellsAvailable;

        return $this;
    }

}
