<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\CampaingTrait;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Repository\RaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RaceRepository::class)]
class Race implements \Stringable
{
    use IdTrait, CampaingTrait;
    use NameTrait;

    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    public ?string $reference = null;
    #[ORM\Column(name: 'sourcet', type: 'string', length: 150, nullable: true)]
    public ?string $source = null;

    /**
     * @var RaceTrait[]
     */
    #[ORM\OneToMany(targetEntity: RaceTrait::class, mappedBy: 'race')]
    public ArrayCollection|iterable $traits;

    /**
     * @var Character[]
     */
    #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'race')]
    public iterable $characters;

    public function __construct()
    {
        $this->traits = new ArrayCollection();
        $this->characters = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string)$this->name;
    }

    public function addTrait(RaceTrait $trait): self
    {
        if (!$this->traits->contains($trait)) {
            $this->traits[] = $trait;
            $trait->setRace($this);
        }

        return $this;
    }

    public function removeTrait(RaceTrait $trait): self
    {
        // set the owning side to null (unless already changed)
        if ($this->traits->removeElement($trait) && $trait->getRace() === $this) {
            $trait->setRace(null);
        }

        return $this;
    }

    public function addCharacter(Character $character): self
    {
        if (!$this->characters->contains($character)) {
            $this->characters[] = $character;
            $character->race = $this;
        }

        return $this;
    }

    public function removeCharacter(Character $character): self
    {
        // set the owning side to null (unless already changed)
        if ($this->characters->removeElement($character) && $character->race === $this) {
            $character->race = null;
        }

        return $this;
    }
}
