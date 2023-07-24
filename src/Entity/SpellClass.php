<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Repository\SpellClassRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;

#[ORM\Table(name: 'spell_class')]
#[ORM\Entity(repositoryClass: SpellClassRepository::class)]
class SpellClass
{
    use IdTrait;

    #[ORM\ManyToOne(targetEntity: Spell::class, inversedBy: 'spell_classes')]
    public ?Spell $spell;
    
    #[ORM\ManyToOne(targetEntity: CharacterClass::class, inversedBy: 'spell_classes')]
    public ?CharacterClass $characterClass;
    
    #[Column(type: 'smallint')]
    public int $level = 0;

    public function __construct(
        ?Spell $spell,
        $characterClass,
        int $level
    ) {
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
