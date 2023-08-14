<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SourceTrait;
use AfmLibre\Pathfinder\Race\Repository\RaceTraitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Entity(repositoryClass: RaceTraitRepository::class)]
class RaceTrait implements Stringable
{
    use NameTrait, IdTrait, SourceTrait;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    public ?iterable $replaces = [];

    public function __construct(#[ORM\ManyToOne(targetEntity: Race::class)]
        public ?Race $race)
    {
    }

    public function __toString(): string
    {
        return (string)$this->name;
    }
}
