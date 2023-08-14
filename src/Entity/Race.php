<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\CampaingTrait;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SourceTrait;
use AfmLibre\Pathfinder\Race\Repository\RaceRepository;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Entity(repositoryClass: RaceRepository::class)]
class Race implements Stringable
{
    use IdTrait, CampaingTrait, SourceTrait;
    use NameTrait;

    public function __toString(): string
    {
        return (string)$this->name;
    }
}
