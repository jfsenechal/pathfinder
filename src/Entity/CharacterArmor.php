<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Character\Repository\CharacterArmorRepository;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Table]
#[ORM\UniqueConstraint(columns: ['character_id', 'armor_id'])]
#[ORM\Entity(repositoryClass: CharacterArmorRepository::class)]
#[UniqueEntity(fields: ['character', 'armor'], message: 'Armure déjà équipée')]
class CharacterArmor implements \Stringable
{
    use IdTrait;

    #[ORM\ManyToOne(targetEntity: Character::class)]
    #[ORM\JoinColumn(name: 'character_id', nullable: false)]
    public ?Character $character;

    #[ORM\ManyToOne(targetEntity: Armor::class)]
    #[ORM\JoinColumn(nullable: false)]
    public ?Armor $armor;

    #[ORM\Column(nullable: true)]
    public ?string $emplacement;

    #[ORM\Column(nullable: false)]
    public bool $trained = true;

    public function __construct(Character $character, Armor $armor)
    {
        $this->character = $character;
        $this->armor = $armor;
    }

    public function __toString(): string
    {
        return (string)$this->armor->name;
    }
}
