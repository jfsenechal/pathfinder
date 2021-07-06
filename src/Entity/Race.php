<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use AfmLibre\Pathfinder\Repository\RaceRepository;

/**
 * @ORM\Entity(repositoryClass=RaceRepository::class)
 */
class Race
{
    use IdTrait;

    /**
     * @var Race[]
     */
    protected $traits;

    public function getId(): ?int
    {
        return $this->id;
    }
}
