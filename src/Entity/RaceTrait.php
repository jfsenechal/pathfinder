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
    protected ?string $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected ?string $description;

    /**
     * @ORM\ManyToOne(targetEntity="AfmLibre\Pathfinder\Entity\Race", inversedBy="traits")
     */
   private ?Race $race;

   public function __construct(Race $race)
   {
       $this->race = $race;
   }

    public function __toString()
    {
        return $this->name;
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

   public function setDescription(?string $description): self
   {
       $this->description = $description;

       return $this;
   }

   public function getRace(): ?Race
   {
       return $this->race;
   }

   public function setRace(?Race $race): self
   {
       $this->race = $race;

       return $this;
   }
}
