<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Repository\SpellProfileCharacterSpellRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Table]
#[ORM\UniqueConstraint(columns: ['spell_profile_id', 'character_spell_id'])]
#[ORM\Entity(repositoryClass: SpellProfileCharacterSpellRepository::class)]
#[UniqueEntity(fields: ['spell_profile', 'character_spell'], message: 'Sort déjà dans votre sélection')]
class SpellProfileCharacterSpell
{
    use IdTrait;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    public function __construct(#[ORM\ManyToOne(targetEntity: SpellProfile::class, inversedBy: 'spell_profile_character_spells')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SpellProfile $spell_profile, #[ORM\ManyToOne(targetEntity: CharacterSpell::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?CharacterSpell $character_spell)
    {
        $this->quantity = 0;
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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
