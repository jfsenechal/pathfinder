<?php


namespace AfmLibre\Pathfinder\Entity\Traits;


use AfmLibre\Pathfinder\Entity\CharacterSpell;
use Doctrine\Common\Collections\ArrayCollection;

trait CharacterSpellsTrait
{
    /**
     * ORM\ManyToMany(targetEntity=CharacterSpell::class)
     * \Doctrine\ORM\Mapping\JoinTable(name="titi")
     * @var CharacterSpell[]|\Doctrine\Common\Collections\ArrayCollection
     */
    private iterable $character_spells;

    public function init() {
        $this->character_spells= new ArrayCollection();
    }

    /**
     * @return \AfmLibre\Pathfinder\Entity\CharacterSpell[]
     */
    public function getCharacterSpells(): iterable
    {
        return $this->character_spells;
    }

    /**
     * @param \AfmLibre\Pathfinder\Entity\CharacterSpell[] $character_spells
     */
    public function setCharacterSpells(iterable $character_spells): void
    {
        $this->character_spells = $character_spells;
    }

    public function addCharacterSpell(CharacterSpell $character_spells): self
    {
        if (!$this->character_spells->contains($character_spells)) {
            $this->character_spells[] = $character_spells;
            //$character_spells->setSpellProfile($this);
        }

        return $this;
    }

    public function removeCharacterSpell(CharacterSpell $character_spells): self
    {
        if ($this->character_spells->removeElement($character_spells)) {
            // set the owning side to null (unless already changed)
            //  if ($character_spells->getSpellProfile() === $this) {
            //      $character_spells->setSpellProfile(null);
            //  }
        }

        return $this;
    }
}
