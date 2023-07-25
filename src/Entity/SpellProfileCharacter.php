<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Repository\SpellProfileCharacterRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Table]
#[ORM\UniqueConstraint(columns: ['spell_profile_id', 'character_spell_id'])]
#[ORM\Entity(repositoryClass: SpellProfileCharacterRepository::class)]
#[UniqueEntity(fields: ['spell_profile', 'character_spell'], message: 'Sort déjà dans votre sélection')]
class SpellProfileCharacter
{
    use IdTrait;

    #[ORM\Column(type: 'integer')]
    public int $quantity = 0;

    #[ORM\ManyToOne(targetEntity: SpellProfile::class, inversedBy: 'spells_profile_character')]
    #[ORM\JoinColumn(nullable: false)]
    public ?SpellProfile $spell_profile;

    #[ORM\ManyToOne(targetEntity: CharacterSpell::class)]
    #[ORM\JoinColumn(nullable: false)]
    public ?CharacterSpell $character_spell;

    public function __construct(SpellProfile $spellProfile, CharacterSpell $characterSpell)
    {
        $this->spell_profile = $spellProfile;
        $this->character_spell = $characterSpell;
    }

}
