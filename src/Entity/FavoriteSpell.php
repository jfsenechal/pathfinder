<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Spell\Repository\FavoriteSpellRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\UniqueConstraint(columns: ['character_id', 'spell_id'])]
#[ORM\Entity(repositoryClass: FavoriteSpellRepository::class)]
#[UniqueEntity(fields: ['character', 'spell'], message: 'Sort déjà dans vos favoris')]
class FavoriteSpell implements Stringable
{
    use IdTrait;

    #[ORM\ManyToOne(targetEntity: Character::class)]
    #[ORM\JoinColumn(name: 'character_id', nullable: false)]
    public ?Character $character;
    #[ORM\ManyToOne(targetEntity: Spell::class)]
    #[ORM\JoinColumn(nullable: false)]
    public ?Spell $spell;
    #[ORM\Column(type: Types::SMALLINT)]
    public int $level;

    public function __construct(
        ?Character $character,
        ?Spell $spell,
        int $level
    ) {
        $this->character = $character;
        $this->spell = $spell;
        $this->level = $level;
    }

    public function __toString(): string
    {
        return (string)$this->spell->name;
    }
}
