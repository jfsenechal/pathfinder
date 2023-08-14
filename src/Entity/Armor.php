<?php

namespace AfmLibre\Pathfinder\Entity;

use Stringable;
use Doctrine\DBAL\Types\Types;
use AfmLibre\Pathfinder\Armor\Repository\ArmorRepository;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SourceTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArmorRepository::class)]
class Armor implements Stringable
{
    use IdTrait, NameTrait, SourceTrait;

    #[ORM\Column(nullable: true)]
    public ?string $cost = null;
    #[ORM\Column(nullable: true)]
    public ?string $bonus = null;
    #[ORM\Column(nullable: true)]
    public ?string $bonus_dexterity_max = null;
    #[ORM\Column(nullable: true)]
    public ?string $penalty = null;//on dexterity or strength
    #[ORM\Column(nullable: true)]
    public ?string $cast_fail = null;
    #[ORM\Column(nullable: true)]
    public ?string $speed9 = null;
    #[ORM\Column(nullable: true)]
    public ?string $speed6 = null;
    #[ORM\Column(nullable: true)]
    public ?string $weight = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $descriptionHtml = null;

    #[ORM\ManyToOne(targetEntity: ArmorCategory::class)]
    #[ORM\JoinColumn]
    public ?ArmorCategory $category = null;

    public function __toString(): string
    {
        return (string) $this->name;
    }

}