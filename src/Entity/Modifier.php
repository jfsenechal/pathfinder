<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Modifier\ModifierListingEnum;
use AfmLibre\Pathfinder\Modifier\Repository\ModifierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\UniqueConstraint(columns: ['object_class', 'object_id', 'ability', 'race_id'])]
#[ORM\Entity(repositoryClass: ModifierRepository::class)]
class Modifier
{
    use IdTrait, NameTrait;

    #[ORM\Column(nullable: false)]
    public ModifierListingEnum $ability;

    #[ORM\Column(nullable: false)]
    public int $value = 0;

    #[ORM\Column(nullable: false)]
    public string $object_class;

    #[ORM\Column(nullable: false)]
    public int $object_id;

    #[ORM\Column(nullable: true)]
    public ?string $description = null;

    #[ORM\ManyToOne(targetEntity: Race::class)]
    #[ORM\JoinColumn(nullable: true)]
    public ?Race $race = null;

    public function __construct(int $objectId, string $objectClassName)
    {
        $this->object_id = $objectId;
        $this->object_class = $objectClassName;
    }

    public function __toString(): string
    {
        return $this->object_class;
    }

}