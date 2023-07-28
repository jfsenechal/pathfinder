<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\CampaingTrait;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SourceTrait;
use AfmLibre\Pathfinder\Repository\ClassRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;

#[ORM\Entity(repositoryClass: ClassRepository::class)]
class ClassT implements \JsonSerializable, \Stringable
{
    use IdTrait, CampaingTrait, SourceTrait;
    use NameTrait;

    #[Column(length: 100, nullable: true)]
    public ?string $shortName = null;

    #[Column(type: 'smallint')]
    public ?int $dieOfLive = null;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $description = null;

    #[ORM\Column(nullable: false)]
    public int $ranksPerLevel;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $alignment;

    #[ORM\OneToMany(targetEntity: Skill::class, mappedBy: 'classT')]
    public iterable $skills;

    #[ORM\OneToMany(targetEntity: Level::class, mappedBy: 'classT')]
    public iterable $levels;

    /**
     * @var SpellClass[]
     */
    #[ORM\OneToMany(targetEntity: SpellClass::class, mappedBy: 'classT')]
    public iterable $spell_classes;

    /**
     * @var Character[]
     */
    #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'classT')]
    public iterable $characters;

    /**
     * @var Spell[] $spells
     */
    public array|ArrayCollection $spells;

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
            $character->classT = $this;
        }

        return $this;
    }

    public function removeCharacter(Character $character): self
    {
        // set the owning side to null (unless already changed)
        if ($this->characters->removeElement($character) && $character->classT === $this) {
            $character->classT = null;
        }

        return $this;
    }

    public function jsonSerialize()
    {
        return ['id' => $this->id];
    }
}
