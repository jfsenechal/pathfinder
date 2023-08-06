<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Armor\Repository\ArmorRepository;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SourceTrait;
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
    public ?string $bonus_dexterity_max;
    #[ORM\Column(nullable: true)]
    public ?string $penalty;//on dexterity or strength
    #[ORM\Column(nullable: true)]
    public ?string $cast_fail;
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