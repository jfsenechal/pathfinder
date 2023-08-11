<?php

namespace AfmLibre\Pathfinder\Entity;

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
    public ?string $cost;

    #[ORM\Column(nullable: true)]
    public ?string $weight = null;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $descriptionHtml = null;

    #[ORM\ManyToOne(targetEntity: ItemCategory::class)]
    #[ORM\JoinColumn(nullable: true)]
    public ?ItemCategory $category = null;

}