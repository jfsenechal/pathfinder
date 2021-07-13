<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Repository\CharacterSpellRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"character_id", "spell_id"})
 * })
 * @ORM\Entity(repositoryClass=CharacterSpellRepository::class)
 * @UniqueEntity(fields={"character_player", "spell"}, message="Sort déjà dans votre sélection")
 */
class CharacterSpell
{
    use IdTrait;

    /**
     * @ORM\ManyToOne(targetEntity=Character::class, inversedBy="character_spells")
     * @ORM\JoinColumn(name="character_id", nullable=false)
     */
    private ?Character $character_player;

    /**
     * @ORM\ManyToOne(targetEntity=Spell::class, inversedBy="characterSpells")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Spell $spell;

    /**
     * @\Doctrine\ORM\Mapping\Column(type="smallint")
     */
    private int $level;

    public function __construct(Character $character, Spell $spell, int $level)
    {
        $this->character_player = $character;
        $this->spell = $spell;
        $this->level = $level;
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

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }
}
