<?php


namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use AfmLibre\Pathfinder\Repository\SchoolRepository;

/**
 * @ORM\Entity(repositoryClass=SchoolRepository::class)
 */
class School
{
    use IdTrait;

    /**
     * @ORM\Column(type="string", length=150)
     */
    protected ?string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
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
}
