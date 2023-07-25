<?php


namespace AfmLibre\Pathfinder\Entity\Traits;


use AfmLibre\Pathfinder\Entity\CharacterSpell;
use AfmLibre\Pathfinder\Entity\SpellProfile;
use Doctrine\Common\Collections\ArrayCollection;

trait CharacterSpellsTrait
{
    /**
     * @var CharacterSpell[]|\Doctrine\Common\Collections\ArrayCollection
     */
    public iterable $character_spells;

    public function init(SpellProfile $spellProfile) {
               $spellProfileCharacters = $spellProfile->spells_profile_character;

        $characterSpells = array_map(
            fn($spellProfileCharacter) => $spellProfileCharacter->getCharacterSpell(),
            $spellProfileCharacters->toArray()
        );

        $this->character_spells= new ArrayCollection($characterSpells);
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
