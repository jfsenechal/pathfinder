<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Modifier\ModifierListingEnum;
use AfmLibre\Pathfinder\Modifier\Repository\ModifierRepository;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\UniqueConstraint(columns: ['object_class', 'object_id', 'ability', 'race_id'])]
#[ORM\Entity(repositoryClass: ModifierRepository::class)]
class Modifier implements Stringable
{
    use IdTrait, NameTrait;

    #[ORM\Column]
    public ModifierListingEnum $ability;

    #[ORM\Column]
    public int $value = 0;

    #[ORM\Column(nullable: true)]
    public ?string $description = null;

    #[ORM\ManyToOne(targetEntity: Race::class)]
    #[ORM\JoinColumn]
    public ?Race $race = null;

    public function __construct(#[ORM\Column(nullable: false)]
        public int $object_id, #[ORM\Column(nullable: false)]
        public string $object_class)
    {
    }

    public function __toString(): string
    {
        return $this->object_class;
    }
}
