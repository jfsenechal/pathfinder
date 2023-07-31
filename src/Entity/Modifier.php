<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Modifier\ModifierEnum;
use AfmLibre\Pathfinder\Repository\ModifierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\UniqueConstraint(columns: ['object_class', 'object_id'])]
#[ORM\Entity(repositoryClass: ModifierRepository::class)]
class Modifier
{
    use IdTrait;

    #[ORM\Column(nullable: false)]
    public ModifierEnum $ability;

    #[ORM\Column(nullable: false)]
    public int $value = 0;

    #[ORM\Column(nullable: false)]
    public string $object_class;

    #[ORM\Column(nullable: false)]
    public int $object_id;

    #[ORM\Column(nullable: true)]
    public ?string $description = null;

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