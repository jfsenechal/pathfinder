<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Repository\CharacterSpellRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Table]
#[ORM\UniqueConstraint(columns: ['character_id', 'spell_id'])]
#[ORM\Entity(repositoryClass: CharacterSpellRepository::class)]
#[UniqueEntity(fields: ['character_player', 'spell'], message: 'Sort déjà dans votre sélection')]
class CharacterSpell implements \Stringable
{
    use IdTrait;

    public function __construct(#[ORM\ManyToOne(targetEntity: Character::class, inversedBy: 'character_spells_available')]
    #[ORM\JoinColumn(name: 'character_id', nullable: false)]
    private ?Character $character_player, #[ORM\ManyToOne(targetEntity: Spell::class, inversedBy: 'character_spells')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Spell $spell, #[ORM\Column(type: 'smallint')]
    private int $level)
    {
    }

    public function __toString(): string
    {
        return (string) $this->spell->getName();
    }

    public function getCharacterPlayer(): ?Character
    {
        return $this->character_player;
    }

    public function setCharacterPlayer(?Character $character_player): self
    {
        $this->character_player = $character_player;

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

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }
}
