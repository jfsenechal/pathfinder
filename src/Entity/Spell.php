<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\CampaingTrait;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SourceTrait;
use AfmLibre\Pathfinder\Spell\Repository\SpellRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Entity(repositoryClass: SpellRepository::class)]
class Spell implements Stringable
{
    use IdTrait, CampaingTrait, SourceTrait;
    use NameTrait;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $description = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $descriptionHtml = null;

    #[ORM\Column(type: Types::STRING, length: 150, nullable: true)]
    public ?string $castringTime = null;
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    public ?string $components = null;
    #[ORM\Column(length: 150, nullable: true)]
    public ?string $ranged = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $target = null;
    #[ORM\Column(type: Types::STRING, length: 150, nullable: true)]
    public ?string $duration = null;
    #[ORM\Column(type: Types::STRING, length: 150, nullable: true)]
    public ?string $savingThrow = null;
    #[ORM\Column(type: Types::STRING, length: 150, nullable: true)]
    public ?string $spellResistance = null;
    #[ORM\Column(type: Types::STRING, length: 150, nullable: true)]
    public ?string $area = null;

    #[ORM\ManyToOne(targetEntity: School::class)]
    #[ORM\JoinColumn]
    public ?School $school = null;

    /**
     * @var Collection<int, ClassSpell>|ClassSpell[]
     */
    #[ORM\OneToMany(targetEntity: ClassSpell::class, mappedBy: 'spell')]
    public iterable $class_spells = [];

    public function __toString(): string
    {
        return (string)$this->name;
    }
    public function __construct()
    {
        $this->class_spells = new ArrayCollection();
    }
}
