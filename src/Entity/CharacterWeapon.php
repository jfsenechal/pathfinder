<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Repository\CharacterWeaponRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Table]
#[ORM\UniqueConstraint(columns: ['character_id', 'weapon_id'])]
#[ORM\Entity(repositoryClass: CharacterWeaponRepository::class)]
#[UniqueEntity(fields: ['character', 'weapon'], message: 'Arme déjà équipée')]
class CharacterWeapon implements \Stringable
{
    use IdTrait;

    #[ORM\ManyToOne(targetEntity: Character::class)]
    #[ORM\JoinColumn(name: 'character_id', nullable: false)]
    public ?Character $character;

    #[ORM\ManyToOne(targetEntity: Weapon::class)]
    #[ORM\JoinColumn(nullable: false)]
    public ?Weapon $weapon;

    #[ORM\Column(nullable: true)]
    public ?string $emplacement;

    public function __construct(Character $character, Weapon $weapon)
    {
        $this->character = $character;
        $this->weapon = $weapon;
    }

    public function __toString(): string
    {
        return (string)$this->weapon->name;
    }
}
