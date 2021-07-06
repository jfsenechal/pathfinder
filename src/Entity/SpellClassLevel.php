<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use AfmLibre\Pathfinder\Repository\SpellClassLevelRepository;

/**
 * @ORM\Entity(repositoryClass=SpellClassLevelRepository::class)
 */
class SpellClassLevel
{
    use IdTrait;

    /**
     * @\Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\Spell")
     */
    private ?Spell $spell;

    /**
     * @\Doctrine\ORM\Mapping\ManyToOne(targetEntity="App\Entity\CharacterClass")
     */
    private ?CharacterClass $characterClass;

    /**
     * @\Doctrine\ORM\Mapping\Column(type="smallint")
     */
    private int $level = 1;

    public function __construct(Spell $spell, CharacterClass $characterClass, int $level)
    {
        $this->spell = $spell;
        $this->characterClass = $characterClass;
        $this->level = $level;
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
