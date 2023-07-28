<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SourceTrait;
use AfmLibre\Pathfinder\Repository\ArmorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArmorRepository::class)]
class Armor
{
    use IdTrait, NameTrait, SourceTrait;

    #[ORM\Column(nullable: true)]
    public ?string $cost;
    #[ORM\Column(nullable: true)]
    public ?string $bonus;
    #[ORM\Column(nullable: true)]
    public ?string $bonusDexMax;
    #[ORM\Column(nullable: true)]
    public ?string $malus;
    #[ORM\Column(nullable: true)]
    public ?string $castFail;
    #[ORM\Column(nullable: true)]
    public ?string $speed9;
    #[ORM\Column(nullable: true)]
    public ?string $speed6;
    #[ORM\Column(nullable: true)]
    public ?string $weight;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $descriptionHtml = null;

    #[ORM\ManyToOne(targetEntity: ArmorCategory::class)]
    #[ORM\JoinColumn(nullable: true)]
    public ?ArmorCategory $category = null;

    public function __toString(): string
    {
        return $this->name;
    }

}