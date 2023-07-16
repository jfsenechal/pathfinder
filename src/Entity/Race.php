<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use AfmLibre\Pathfinder\Repository\RaceRepository;

#[ORM\Entity(repositoryClass: RaceRepository::class)]
class Race implements \Stringable
{
    use IdTrait;
    use NameTrait;

    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    protected ?string $reference = null;
    #[ORM\Column(name: 'sourcet', type: 'string', length: 150, nullable: true)]
    protected ?string $source = null;

    /**
     * @var RaceTrait[]
     */
    #[ORM\OneToMany(targetEntity: RaceTrait::class, mappedBy: 'race')]
    protected $traits;

    /**
     * @var Character[]
     */
    #[ORM\OneToMany(targetEntity: Character::class, mappedBy: 'race')]
    private iterable $characters;

    public function __construct()
    {
        $this->traits = new ArrayCollection();
        $this->characters = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return Collection|RaceTrait[]
     */
    public function getTraits(): Collection
    {
        return $this->traits;
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

    /**
     * @return Collection|Character[]
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function setCharacters(?Character $characters): self
    {
        $this->characters = $characters;

        return $this;
    }

    public function addCharacter(Character $character): self
    {
        if (!$this->characters->contains($character)) {
            $this->characters[] = $character;
            $character->setRace($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): self
    {
        // set the owning side to null (unless already changed)
        if ($this->characters->removeElement($character) && $character->getRace() === $this) {
            $character->setRace(null);
        }

        return $this;
    }
}
