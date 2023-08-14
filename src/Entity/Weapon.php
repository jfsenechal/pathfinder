<?php

namespace AfmLibre\Pathfinder\Entity;

use Stringable;
use Doctrine\DBAL\Types\Types;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SourceTrait;
use AfmLibre\Pathfinder\Weapon\Repository\WeaponRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeaponRepository::class)]
class Weapon implements Stringable
{
    use IdTrait, NameTrait, SourceTrait;

    #[ORM\Column(length: 150)]
    public string $cost;
    #[ORM\Column(length: 150)]
    public string $damageSmall;
    #[ORM\Column(length: 150)]
    public string $damageMedium;
    #[ORM\Column(length: 150)]
    public string $critical;
    #[ORM\Column(length: 150)]
    public string $ranged;
    #[ORM\Column(length: 150)]
    public string $weight;
    #[ORM\Column(length: 150)]
    public string $typed;
    #[ORM\Column(length: 150)]
    public string $special;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $descriptionHtml = null;

    #[ORM\Column(length: 150, nullable: true)]
    public string $category2;

    #[ORM\ManyToOne(targetEntity: WeaponCategory::class)]
    #[ORM\JoinColumn]
    public ?WeaponCategory $category = null;

    //todo determin
    public $distance = false;

    public function __toString(): string
    {
        return (string) $this->name;
    }

}