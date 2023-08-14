<?php

namespace AfmLibre\Pathfinder\Entity;

use JsonSerializable;
use Stringable;
use Doctrine\DBAL\Types\Types;
use AfmLibre\Pathfinder\Classes\Repository\ClassRepository;
use AfmLibre\Pathfinder\Entity\Traits\CampaingTrait;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SourceTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;

#[ORM\Entity(repositoryClass: ClassRepository::class)]
class ClassT implements JsonSerializable, Stringable
{
    use IdTrait, CampaingTrait, SourceTrait;
    use NameTrait;

    #[Column(length: 100, nullable: true)]
    public ?string $shortName = null;

    #[Column(type: Types::SMALLINT)]
    public ?int $dieOfLive = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $description = null;

    #[ORM\Column]
    public int $ranksPerLevel;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $alignment = null;

    /**
     * @var Spell[] $spells
     */
    public array|ArrayCollection $spells;

    public function __construct()
    {
        $this->spells = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string)$this->name;
    }

    public function jsonSerialize(): mixed
    {
        return ['id' => $this->id];
    }
}
