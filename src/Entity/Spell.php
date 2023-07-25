<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\CampaingTrait;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Repository\SpellRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpellRepository::class)]
class Spell implements \Stringable
{
    use IdTrait, CampaingTrait;
    use NameTrait;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $description = null;
    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $descriptionHtml = null;
    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    public ?string $reference = null;
    #[ORM\Column(name: 'sourcet', type: 'string', length: 150, nullable: true)]
    public ?string $source = null;
    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    public ?string $castringTime = null;
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    public ?string $components = null;
    #[ORM\Column(name: 'ranget', type: 'string', length: 150, nullable: true)]
    public ?string $range = null;
    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $target = null;
    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    public ?string $duration = null;
    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    public ?string $savingThrow = null;
    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    public ?string $spellResistance = null;
    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    public ?string $area = null;

    #[ORM\ManyToOne(targetEntity: School::class)]
    #[ORM\JoinColumn(nullable: true)]
    public ?School $school = null;

    /**
     * @var SpellClass[]
     */
    #[ORM\OneToMany(targetEntity: SpellClass::class, mappedBy: 'spell')]
    public iterable $spell_classes;

    /**
     * @var CharacterSpell[]
     */
    #[ORM\OneToMany(targetEntity: CharacterSpell::class, mappedBy: 'spell', orphanRemoval: true)]
    public iterable $character_spells;

    public function __construct()
    {
        $this->spell_classes = new ArrayCollection();
        $this->character_spells = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string)$this->name;
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
        // set the owning side to null (unless already changed)
        if ($this->spell_classes->removeElement($spellClass) && $spellClass->getSpell() === $this) {
            $spellClass->setSpell(null);
        }

        return $this;
    }


    public function addCharacterSpell(CharacterSpell $characterSpell): self
    {
        if (!$this->character_spells->contains($characterSpell)) {
            $this->character_spells[] = $characterSpell;
            $characterSpell->spell = $this;
        }

        return $this;
    }

    public function removeCharacterSpell(CharacterSpell $characterSpell): self
    {
        // set the owning side to null (unless already changed)
        if ($this->character_spells->removeElement($characterSpell) && $characterSpell->spell === $this) {
            $characterSpell->spell = null;
        }

        return $this;
    }
}
