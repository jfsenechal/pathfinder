<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Attack\AttackRoll;
use AfmLibre\Pathfinder\Attack\DamageRoll;
use AfmLibre\Pathfinder\Character\Repository\CharacterWeaponRepository;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\UniqueConstraint(columns: ['character_id', 'weapon_id'])]
#[ORM\Entity(repositoryClass: CharacterWeaponRepository::class)]
#[UniqueEntity(fields: ['character', 'weapon'], message: 'Arme déjà équipée')]
class CharacterWeapon implements Stringable
{
    use IdTrait;

    #[ORM\Column(nullable: true)]
    public ?string $emplacement = null;

    #[ORM\Column(nullable: true)]
    public ?bool $leading_hand = null;

    #[ORM\Column]
    public bool $trained = true;

    public ?AttackRoll $attackRoll = null;
    public ?DamageRoll $damageRoll = null;

    public function __construct(#[ORM\ManyToOne(targetEntity: Character::class)]
        #[ORM\JoinColumn(name: 'character_id', nullable: false)]
        public ?Character $character, #[ORM\ManyToOne(targetEntity: Weapon::class)]
        #[ORM\JoinColumn(nullable: false)]
        public Weapon $weapon)
    {
    }

    public function __toString(): string
    {
        return (string)$this->weapon->name;
    }
}
