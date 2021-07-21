<?php


namespace AfmLibre\Pathfinder\Spell\Dto;

use AfmLibre\Pathfinder\Entity\CharacterSpell;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class SpellProfileSelectionDto
{
    private iterable $quantities;
    private iterable $spells;
    private iterable $spellsAvailable;

    public function __construct(array $characterSpells, array $characterSpellsAvailable)
    {
        $this->quantities = new ArrayCollection();
        $this->spells = $characterSpells;
        $this->spellsAvailable = $characterSpellsAvailable;
        $this->initQuantities();
    }

    private function initQuantities()
    {
        foreach ($this->spells as $characterSpell) {
            $tag1 = new QuantityDto($characterSpell->getId());
            $this->getQuantities()->add($tag1);
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
