<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\CampaingTrait;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SourceTrait;
use AfmLibre\Pathfinder\Repository\FeatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FeatRepository::class)]
class Feat
{
    use IdTrait, NameTrait,CampaingTrait, SourceTrait;

    #[ORM\Column(length: 255, nullable: true)]
    public ?string $summary;
    #[ORM\Column(length: 150, nullable: true)]
    public ?string $category = null;
    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $conditions = null;
    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $advantage = null;
    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $advantageHtml = null;
    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $special = null;
    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $normal = null;

    #[ORM\Column(type: 'json')]
    public array $requires;

    // only used during YAML import
    //public List<String> requiresRef;
    // only used in specific view
    //public int depth;


    public function __toString(): string
    {
        return $this->name;
    }
}
