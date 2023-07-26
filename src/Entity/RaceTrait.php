<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Repository\RaceTraitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RaceTraitRepository::class)]
class RaceTrait implements \Stringable
{
    use NameTrait, IdTrait;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?iterable $replaces = [];

    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    public ?string $reference = null;

    #[ORM\Column(name: 'sourcet', type: 'string', length: 150, nullable: true)]
    public ?string $source = null;

    #[ORM\ManyToOne(targetEntity: Race::class, inversedBy: 'traits')]
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
