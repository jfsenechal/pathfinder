<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Repository\SpellRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpellRepository::class)
 */
class Spell
{
    use IdTrait;
    use NameTrait;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected ?string $description;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected ?string $descriptionHtml;
    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    protected ?string $reference;
    /**
     * @ORM\Column(name="sourcet", type="string", length=150, nullable=true)
     */
    protected ?string $source;
    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private ?string $castringTime;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $components;
    /**
     * @ORM\Column(name="ranget", type="string", length=150, nullable=true)
     */
    private ?string $range;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $target;
    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private ?string $duration;
    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private ?string $savingThrow;
    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private ?string $spellResistance;
    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private ?string $area;

    /**
     * @ORM\ManyToOne(targetEntity=School::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private ?School $school;

    /**
     * @ORM\OneToMany(targetEntity=SpellClass::class, mappedBy="spell")
     * @var SpellClass[]
     */
    private iterable $spell_classes;

    /**
     * @ORM\OneToMany(targetEntity=CharacterSpell::class, mappedBy="spell", orphanRemoval=true)
     * @var CharacterSpell[]
     */
    private $character_spells;

    public function __construct()
    {
        $this->spell_classes = new ArrayCollection();
        $this->character_spells = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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

    public function getSchool(): ?School
    {
        return $this->school;
    }

    public function setSchool(?School $school): self
    {
        $this->school = $school;

        return $this;
    }

    public function getCastringTime(): ?string
    {
        return $this->castringTime;
    }

    public function setCastringTime(?string $castringTime): self
    {
        $this->castringTime = $castringTime;

        return $this;
    }

    public function getComponents(): ?string
    {
        return $this->components;
    }

    public function setComponents(?string $components): self
    {
        $this->components = $components;

        return $this;
    }

    public function getRange(): ?string
    {
        return $this->range;
    }

    public function setRange(?string $range): self
    {
        $this->range = $range;

        return $this;
    }

    public function getTarget(): ?string
    {
        return $this->target;
    }

    public function setTarget(?string $target): self
    {
        $this->target = $target;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getSavingThrow(): ?string
    {
        return $this->savingThrow;
    }

    public function setSavingThrow(?string $savingThrow): self
    {
        $this->savingThrow = $savingThrow;

        return $this;
    }

    public function getSpellResistance(): ?string
    {
        return $this->spellResistance;
    }

    public function setSpellResistance(?string $spellResistance): self
    {
        $this->spellResistance = $spellResistance;

        return $this;
    }

    public function getArea(): ?string
    {
        return $this->area;
    }

    public function setArea(?string $area): self
    {
        $this->area = $area;

        return $this;
    }

    /**
     * @return Collection|SpellClass[]
     */
    public function getSpellClasses(): Collection
    {
        return $this->spell_classes;
    }

    public function addSpellClass(SpellClass $spellClass): self
    {
        if (!$this->spell_classes->contains($spellClass)) {
            $this->spell_classes[] = $spellClass;
            $spellClass->setSpell($this);
        }

        return $this;
    }

    public function removeSpellClass(SpellClass $spellClass): self
    {
        if ($this->spell_classes->removeElement($spellClass)) {
            // set the owning side to null (unless already changed)
            if ($spellClass->getSpell() === $this) {
                $spellClass->setSpell(null);
            }
        }

        return $this;
    }

    public function getDescriptionHtml(): ?string
    {
        return $this->descriptionHtml;
    }

    public function setDescriptionHtml(?string $descriptionHtml): self
    {
        $this->descriptionHtml = $descriptionHtml;

        return $this;
    }

    /**
     * @return Collection|CharacterSpell[]
     */
    public function getCharacterSpells(): Collection
    {
        return $this->character_spells;
    }

    public function addCharacterSpell(CharacterSpell $characterSpell): self
    {
        if (!$this->character_spells->contains($characterSpell)) {
            $this->character_spells[] = $characterSpell;
            $characterSpell->setSpell($this);
        }

        return $this;
    }

    public function removeCharacterSpell(CharacterSpell $characterSpell): self
    {
        if ($this->character_spells->removeElement($characterSpell)) {
            // set the owning side to null (unless already changed)
            if ($characterSpell->getSpell() === $this) {
                $characterSpell->setSpell(null);
            }
        }

        return $this;
    }
}
