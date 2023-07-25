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
    use IdTrait, CampaingTrait;
    use NameTrait;

    #[Column( length: 100, nullable: true)]
    public ?string $shortName = null;

    #[Column(type: 'smallint')]
    public ?int $dieOfLive = null;

    #[ORM\Column( length: 150, nullable: true)]
    public ?string $reference = null;
    #[ORM\Column(name: 'sourcet',  length: 150, nullable: true)]
    public ?string $source = null;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $description = null;

    #[ORM\Column(nullable: false)]
    public int $ranksPerLevel;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $alignment;

    #[ORM\OneToMany(targetEntity: Skill::class, mappedBy: 'characterClass')]
    public iterable $skills;
    
    #[ORM\OneToMany(targetEntity: Level::class, mappedBy: 'characterClass')]
    public iterable $levels;

    /**
     * @var SpellClass[]
     */
    #[ORM\OneToMany(targetEntity: SpellClass::class, mappedBy: 'characterClass')]
    public iterable $spell_classes;

    /**
     * @var Character[]
     */
    #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'characterClass')]
    public iterable $characters;

    public function __construct()
    {
        $this->spells = new ArrayCollection();
        $this->spell_classes = new ArrayCollection();
        $this->characters = new ArrayCollection();
        $this->skills = new ArrayCollection();
        $this->levels = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string)$this->name;
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

    public function addCharacter(Character $character): self
    {
        if (!$this->characters->contains($character)) {
            $this->characters[] = $character;
            $character->characterClass = $this;
        }

        return $this;
    }

    public function removeCharacter(Character $character): self
    {
        // set the owning side to null (unless already changed)
        if ($this->characters->removeElement($character) && $character->characterClass === $this) {
            $character->characterClass =null;
        }

        return $this;
    }

    public function jsonSerialize()
    {
        return ['id' => $this->id];
    }
}
