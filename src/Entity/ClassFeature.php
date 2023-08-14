<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Classes\Repository\ClassFeatureRepository;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SourceTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Entity(repositoryClass: ClassFeatureRepository::class)]
class ClassFeature implements Stringable
{
    use IdTrait;
    use NameTrait, SourceTrait;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    public bool $auto = false;

    public function __construct(#[ORM\ManyToOne(targetEntity: ClassT::class)]
        public ?ClassT $classT, #[ORM\ManyToOne(targetEntity: Level::class)]
        public ?Level $level)
    {
    }

    public function __toString(): string
    {
        return (string)$this->name;
    }
}
