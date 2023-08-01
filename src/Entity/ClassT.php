<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\CampaingTrait;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SourceTrait;
use AfmLibre\Pathfinder\Repository\ClassRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;

#[ORM\Entity(repositoryClass: ClassRepository::class)]
class ClassT implements \JsonSerializable, \Stringable
{
    use IdTrait, CampaingTrait, SourceTrait;
    use NameTrait;

    #[Column(length: 100, nullable: true)]
    public ?string $shortName = null;

    #[Column(type: 'smallint')]
    public ?int $dieOfLive = null;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $description = null;

    #[ORM\Column(nullable: false)]
    public int $ranksPerLevel;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $alignment;

    #[ORM\OneToMany(targetEntity: Level::class, mappedBy: 'classT')]
    public iterable $levels;

    /**
     * @var ClassSpell[]
     */
    #[ORM\OneToMany(targetEntity: ClassSpell::class, mappedBy: 'classT')]
    public iterable $class_spells;
    /**
     * @var ClassSkill[]
     */
    #[ORM\OneToMany(targetEntity: ClassSkill::class, mappedBy: 'classT')]
    public iterable $class_skills;

    /**
     * @var Spell[] $spells
     */
    public array|ArrayCollection $spells;

    public function __construct()
    {
        $this->spells = new ArrayCollection();
        $this->class_spells = new ArrayCollection();
        $this->class_skills = new ArrayCollection();
        $this->levels = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string)$this->name;
    }

    public function jsonSerialize()
    {
        return ['id' => $this->id];
    }
}
