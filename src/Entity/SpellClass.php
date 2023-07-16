<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use AfmLibre\Pathfinder\Repository\SpellClassRepository;

#[ORM\Table(name: 'spell_class')]
#[ORM\Entity(repositoryClass: SpellClassRepository::class)]
class SpellClass
{
    use IdTrait;

    public function __construct(#[ORM\ManyToOne(targetEntity: Spell::class, inversedBy: 'spell_classes')]
    private ?Spell $spell, #[ORM\ManyToOne(targetEntity: CharacterClass::class, inversedBy: 'spell_classes')]
    private ?CharacterClass $characterClass, #[\Doctrine\ORM\Mapping\Column(type: 'smallint')]
    private int $level)
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getSpell(): ?Spell
    {
        return $this->spell;
    }

    public function setSpell(?Spell $spell): self
    {
        $this->spell = $spell;

        return $this;
    }

    public function getCharacterClass(): ?CharacterClass
    {
        return $this->characterClass;
    }

    public function setCharacterClass(?CharacterClass $characterClass): self
    {
        $this->characterClass = $characterClass;

        return $this;
    }
}
