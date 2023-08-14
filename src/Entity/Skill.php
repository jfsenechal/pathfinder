<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\CampaingTrait;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SourceTrait;
use AfmLibre\Pathfinder\Skill\Repository\SkillRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
class Skill implements Stringable
{
    use IdTrait, CampaingTrait, SourceTrait;
    use NameTrait;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $descriptionHtml = null;

    #[ORM\Column(nullable: true)]
    public ?string $ability = null;

    #[ORM\Column]
    public bool $training_needed;

    public function __toString(): string
    {
        return (string) $this->name;
    }
}
