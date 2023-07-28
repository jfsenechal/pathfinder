<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SourceTrait;
use AfmLibre\Pathfinder\Repository\WeaponRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeaponRepository::class)]
class Weapon
{
    use IdTrait, NameTrait, SourceTrait;

    #[ORM\Column(length: 150, nullable: false)]
    public string $cost;
    #[ORM\Column(length: 150, nullable: false)]
    public string $damageSmall;
    #[ORM\Column(length: 150, nullable: false)]
    public string $damageMedium;
    #[ORM\Column(length: 150, nullable: false)]
    public string $critical;
    #[ORM\Column(length: 150, nullable: false)]
    public string $ranged;
    #[ORM\Column(length: 150, nullable: false)]
    public string $weight;
    #[ORM\Column(length: 150, nullable: false)]
    public string $typed;
    #[ORM\Column(length: 150, nullable: false)]
    public string $special;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $descriptionHtml = null;


    #[ORM\Column(length: 150, nullable: true)]
    public string $category2;

    #[ORM\ManyToOne(targetEntity: WeaponCategory::class)]
    #[ORM\JoinColumn(nullable: true)]
    public ?WeaponCategory $category = null;

    public function __toString(): string
    {
        return $this->name;
    }

}