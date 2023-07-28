<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\CampaingTrait;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SourceTrait;
use AfmLibre\Pathfinder\Repository\RaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RaceRepository::class)]
class Race implements \Stringable
{
    use IdTrait, CampaingTrait, SourceTrait;
    use NameTrait;

    /**
     * @var RaceTrait[]
     */
    #[ORM\OneToMany(targetEntity: RaceTrait::class, mappedBy: 'race')]
    public ArrayCollection|iterable $traits;

    public function __construct()
    {
        $this->traits = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string)$this->name;
    }

    public function addTrait(RaceTrait $trait): self
    {
        if (!$this->traits->contains($trait)) {
            $this->traits[] = $trait;
            $trait->race = $this;
        }

        return $this;
    }

    public function removeTrait(RaceTrait $trait): self
    {
        // set the owning side to null (unless already changed)
        if ($this->traits->removeElement($trait) && $trait->race === $this) {
            $trait->race = null;
        }

        return $this;
    }
}
