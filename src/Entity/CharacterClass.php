<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use AfmLibre\Pathfinder\Repository\CharacterClassRepository;

/**
 * @ORM\Entity(repositoryClass=CharacterClassRepository::class)
 */
class CharacterClass
{
    use IdTrait;
    use NameTrait;

    /**
     * @\Doctrine\ORM\Mapping\Column(type="string", length=100, nullable=true)
     */
    private ?string $short_name;

    /**
     * @\Doctrine\ORM\Mapping\Column(type="smallint")
     */
    private ?int $die_of_live;

    /**
     * @ORM\ManyToMany(targetEntity=Spell::class, mappedBy="character_class")
     */
    private $spells;

    public function __construct()
    {
        $this->spells = new ArrayCollection();
    }

    /**
     * @return Collection|Spell[]
     */
    public function getSpells(): Collection
    {
        return $this->spells;
    }

    public function addSpell(Spell $spell): self
    {
        if (!$this->spells->contains($spell)) {
            $this->spells[] = $spell;
            $spell->addCharacterClass($this);
        }

        return $this;
    }

    public function removeSpell(Spell $spell): self
    {
        if ($this->spells->removeElement($spell)) {
            $spell->removeCharacterClass($this);
        }

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->short_name;
    }

    public function setShortName(string $short_name): self
    {
        $this->short_name = $short_name;

        return $this;
    }

    public function getDieOfLive(): ?int
    {
        return $this->die_of_live;
    }

    public function setDieOfLive(int $die_of_live): self
    {
        $this->die_of_live = $die_of_live;

        return $this;
    }


}
