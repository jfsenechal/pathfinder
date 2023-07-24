<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Repository\ClassFeatureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassFeatureRepository::class)]
class ClassFeature implements \Stringable
{
    use IdTrait;
    use NameTrait;

    #[ORM\Column(nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: 'string', length: 250, nullable: true)]
    public ?string $reference = null;

    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    public ?string $source = null;

    #[ORM\Column(type: 'boolean')]
    public bool $auto = false;

    #[ORM\ManyToOne(targetEntity: CharacterClass::class)]
    public ?CharacterClass $characterClass;

    #[ORM\ManyToOne(targetEntity: Level::class)]
    public ?Level $level;

    public function __construct(CharacterClass $characterClass, Level $level)
    {
        $this->characterClass = $characterClass;
        $this->level = $level;
    }

    public function __toString(): string
    {
        return (string)$this->name;
    }
}
