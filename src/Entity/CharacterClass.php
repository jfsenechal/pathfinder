<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Repository\CharacterClassRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CharacterClassRepository::class)
 */
class CharacterClass
{
    use IdTrait;
    use NameTrait;

    /**
     * @\Doctrine\ORM\Mapping\Column(type="string", length=100, nullable=true)
     */
    private ?string $short_name;

    /**
     * @\Doctrine\ORM\Mapping\Column(type="smallint")
     */
    private ?int $die_of_live;

    /**
     * @ORM\OneToMany(targetEntity=SpellClassLevel::class, mappedBy="characterClass")
     * @var SpellClassLevel[]
     */
    private iterable $spell_classes;

    public function __construct()
    {
        $this->spells = new ArrayCollection();
        $this->spell_classes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getShortName(): ?string
    {
        return $this->short_name;
    }

    public function setShortName(?string $short_name): self
    {
        $this->short_name = $short_name;

        return $this;
    }

    public function getDieOfLive(): ?int
    {
        return $this->die_of_live;
    }

    public function setDieOfLive(int $die_of_live): self
    {
        $this->die_of_live = $die_of_live;

        return $this;
    }

    /**
     * @return Collection|SpellClassLevel[]
     */
    public function getSpellClasses(): Collection
    {
        return $this->spell_classes;
    }

    public function addSpellClass(SpellClassLevel $spellClass): self
    {
        if (!$this->spell_classes->contains($spellClass)) {
            $this->spell_classes[] = $spellClass;
            $spellClass->setCharacterClass($this);
        }

        return $this;
    }

    public function removeSpellClass(SpellClassLevel $spellClass): self
    {
        if ($this->spell_classes->removeElement($spellClass)) {
            // set the owning side to null (unless already changed)
            if ($spellClass->getCharacterClass() === $this) {
                $spellClass->setCharacterClass(null);
            }
        }

        return $this;
    }

}
