<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\CampaingTrait;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SourceTrait;
use AfmLibre\Pathfinder\Skill\Repository\SkillRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
class Skill
{
    use IdTrait, CampaingTrait, SourceTrait;
    use NameTrait;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $description = null;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $descriptionHtml = null;

    #[ORM\Column(nullable: true)]
    public ?string $ability;

    #[ORM\Column(type: 'text', nullable: true)]
    public bool $training;

    public function __toString(): string
    {
        return $this->name;
    }
}
