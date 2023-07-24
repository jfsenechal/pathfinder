<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\CampaingTrait;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Repository\CharacterClassRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;

#[ORM\Entity(repositoryClass: CharacterClassRepository::class)]
class CharacterClass implements \JsonSerializable, \Stringable
{
    /**
     * @var Spell[] $spells
     */
    public array|ArrayCollection $spells;
    use IdTrait,CampaingTrait;
    use NameTrait;

    #[Column(type: 'string', length: 100, nullable: true)]
    private ?string $short_name = null;

    #[Column(type: 'smallint')]
    private ?int $die_of_live = null;

    /**
     * @var SpellClass[]
     */
    #[ORM\OneToMany(targetEntity: SpellClass::class, mappedBy: 'characterClass')]
    private iterable $spell_classes;

    /**
     * @var Character[]
     */
    #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'characterClass')]
    private iterable $characters;

    public function __construct()
    {
        $this->spells = new ArrayCollection();
        $this->spell_classes = new ArrayCollection();
        $this->characters = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string)$this->name;
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
     * @return Collection|SpellClass[]
     */
    public function getSpellClasses(): Collection
    {
        return $this->spell_classes;
    }

    public function addSpellClass(SpellClass $spellClass): self
    {
        if (!$this->spell_classes->contains($spellClass)) {
            $this->spell_classes[] = $spellClass;
            $spellClass->setCharacterClass($this);
        }

        return $this;
    }

    public function removeSpellClass(SpellClass $spellClass): self
    {
        // set the owning side to null (unless already changed)
        if ($this->spell_classes->removeElement($spellClass) && $spellClass->getCharacterClass() === $this) {
            $spellClass->setCharacterClass(null);
        }

        return $this;
    }

    /**
     * @return Collection|Character[]
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function addCharacter(Character $character): self
    {
        if (!$this->characters->contains($character)) {
            $this->characters[] = $character;
            $character->setCharacterClass($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): self
    {
        // set the owning side to null (unless already changed)
        if ($this->characters->removeElement($character) && $character->getCharacterClass() === $this) {
            $character->setCharacterClass(null);
        }

        return $this;
    }

    public function jsonSerialize()
    {
        return ['id' => $this->id];
    }
}
