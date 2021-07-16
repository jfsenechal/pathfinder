<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Repository\CharacterSpellRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use AfmLibre\Pathfinder\Repository\SpellProfileCharacterRepository;

/**
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"spell_profile_id", "character_spell_id"})
 * })
 * @ORM\Entity(repositoryClass=SpellProfileCharacterRepository::class)
 * @UniqueEntity(fields={"spell_profile", "character_spell"}, message="Sort déjà dans votre sélection")
 */
class SpellProfileCharacterSpell
{
    use IdTrait;

    /**
     * @ORM\ManyToOne(targetEntity=SpellProfile::class, inversedBy="character_spells")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?SpellProfile $spell_profile;

    /**
     * @ORM\ManyToOne(targetEntity=CharacterSpell::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?CharacterSpell $character_spell;

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

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getSpellProfile(): ?SpellProfile
    {
        return $this->spell_profile;
    }

    public function setSpellProfile(?SpellProfile $spell_profile): self
    {
        $this->spell_profile = $spell_profile;

        return $this;
    }

    public function getCharacterSpell(): ?CharacterSpell
    {
        return $this->character_spell;
    }

    public function setCharacterSpell(?CharacterSpell $character_spell): self
    {
        $this->character_spell = $character_spell;

        return $this;
    }
}
