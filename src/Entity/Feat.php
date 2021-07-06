<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use AfmLibre\Pathfinder\Repository\FeatRepository;

/**
 * Exploit
 * @ORM\Entity(repositoryClass=FeatRepository::class)
 */
class Feat
{
    use IdTrait;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $category;
    /**
     * @ORM\Column(type="string", length=150)
     */
    private $conditions;
    /**
     * @ORM\Column(type="string", length=150)
     */
    private $advantage;
    /**
     * @ORM\Column(type="string", length=150)
     */
    private $special;
    /**
     * @ORM\Column(type="string", length=150)
     */
    private $normal;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getConditions(): ?string
    {
        return $this->conditions;
    }

    public function setConditions(string $conditions): self
    {
        $this->conditions = $conditions;

        return $this;
    }

    public function getAdvantage(): ?string
    {
        return $this->advantage;
    }

    public function setAdvantage(string $advantage): self
    {
        $this->advantage = $advantage;

        return $this;
    }

    public function getSpecial(): ?string
    {
        return $this->special;
    }

    public function setSpecial(string $special): self
    {
        $this->special = $special;

        return $this;
    }

    public function getNormal(): ?string
    {
        return $this->normal;
    }

    public function setNormal(string $normal): self
    {
        $this->normal = $normal;

        return $this;
    }
}
