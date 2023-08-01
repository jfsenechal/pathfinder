<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\CampaingTrait;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SourceTrait;
use AfmLibre\Pathfinder\Repository\SpellRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpellRepository::class)]
class Spell implements \Stringable
{
    use IdTrait, CampaingTrait, SourceTrait;
    use NameTrait;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $description = null;
    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $descriptionHtml = null;

    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    public ?string $castringTime = null;
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    public ?string $components = null;
    #[ORM\Column(length: 150, nullable: true)]
    public ?string $ranged = null;
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
     * @var ClassSpell[]
     */
    #[ORM\OneToMany(targetEntity: ClassSpell::class, mappedBy: 'spell')]
    public iterable $class_spells;

    /**
     * @var CharacterSpell[]
     */
    #[ORM\OneToMany(targetEntity: CharacterSpell::class, mappedBy: 'spell', orphanRemoval: true)]
    public iterable $character_spells;

    public function __construct()
    {
        $this->class_spells = new ArrayCollection();
        $this->character_spells = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string)$this->name;
    }

}
