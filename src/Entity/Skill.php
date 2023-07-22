<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Repository\SkillRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
class Skill
{
    use IdTrait;
    use NameTrait;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $description = null;
    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $descriptionHtml = null;

    #[ORM\Column(nullable: true)]
    public ?string $ability;

    #[ORM\Column(type: 'text', nullable: true)]
    public bool $training;

    #[ORM\Column(name: 'sourcet', type: 'string', length: 150, nullable: true)]
    public ?string $source = null;

    public function __toString(): string
    {
        return $this->name;
    }
}
