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

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: 'string', length: 250, nullable: true)]
    public ?string $reference = null;

    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    public ?string $source = null;

    #[ORM\Column(type: 'boolean')]
    public bool $auto = false;

    #[ORM\ManyToOne(targetEntity: ClassT::class)]
    public ?ClassT $classT;

    #[ORM\ManyToOne(targetEntity: Level::class)]
    public ?Level $level;

    public function __construct(ClassT $classT, Level $level)
    {
        $this->classT = $classT;
        $this->level = $level;
    }

    public function __toString(): string
    {
        return (string)$this->name;
    }
}
