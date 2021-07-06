<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use AfmLibre\Pathfinder\Repository\RaceTraitRepository;

/**
 * @ORM\Entity(repositoryClass=RaceTraitRepository::class)
 */
class RaceTrait
{
    use IdTrait;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $name;
    /**
     * @ORM\Column(type="string", length=150)
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
