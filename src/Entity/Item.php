<?php

namespace AfmLibre\Pathfinder\Entity;

use Doctrine\DBAL\Types\Types;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SourceTrait;
use AfmLibre\Pathfinder\Item\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    use IdTrait, NameTrait, SourceTrait;

    #[ORM\Column(nullable: true)]
    public ?string $cost = null;

    #[ORM\Column(nullable: true)]
    public ?string $weight = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $descriptionHtml = null;

    #[ORM\ManyToOne(targetEntity: ItemCategory::class)]
    #[ORM\JoinColumn]
    public ?ItemCategory $category = null;

}