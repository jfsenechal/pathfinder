<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SourceTrait;
use AfmLibre\Pathfinder\Race\Repository\RaceTraitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RaceTraitRepository::class)]
class RaceTrait implements \Stringable
{
    use NameTrait, IdTrait, SourceTrait;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: 'json', nullable: true)]
    public ?iterable $replaces = [];

    #[ORM\ManyToOne(targetEntity: Race::class)]
    public ?Race $race;

    public function __construct(Race $race)
    {
        $this->race = $race;
    }

    public function __toString(): string
    {
        return (string)$this->name;
    }
}
