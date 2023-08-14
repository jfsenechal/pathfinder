<?php

namespace AfmLibre\Pathfinder\Entity;

use Stringable;
use Doctrine\DBAL\Types\Types;
use AfmLibre\Pathfinder\Entity\Traits\CampaingTrait;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SourceTrait;
use AfmLibre\Pathfinder\Skill\Repository\SkillRepository;
use Doctrine\ORM\Mapping as ORM;

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
