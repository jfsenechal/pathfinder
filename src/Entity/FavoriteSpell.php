<?php

namespace AfmLibre\Pathfinder\Entity;

use Stringable;
use Doctrine\DBAL\Types\Types;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Spell\Repository\FavoriteSpellRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\UniqueConstraint(columns: ['character_id', 'spell_id'])]
#[ORM\Entity(repositoryClass: FavoriteSpellRepository::class)]
#[UniqueEntity(fields: ['character', 'spell'], message: 'Sort déjà dans vos favoris')]
class FavoriteSpell implements Stringable
{
    use IdTrait;

    public function __construct(#[ORM\ManyToOne(targetEntity: Character::class)]
    #[ORM\JoinColumn(name: 'character_id', nullable: false)]
    public ?Character $character, #[ORM\ManyToOne(targetEntity: Spell::class)]
    #[ORM\JoinColumn(nullable: false)]
    public ?Spell $spell, #[ORM\Column(type: Types::SMALLINT)]
    public int $level)
    {
    }

    public function __toString(): string
    {
        return (string)$this->spell->name;
    }
}
