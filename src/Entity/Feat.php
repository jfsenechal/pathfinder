<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\CampaingTrait;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Entity\Traits\SourceTrait;
use AfmLibre\Pathfinder\Feat\Repository\FeatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Entity(repositoryClass: FeatRepository::class)]
class Feat implements Stringable
{
    use IdTrait, NameTrait,CampaingTrait, SourceTrait;

    #[ORM\Column(length: 255, nullable: true)]
    public ?string $summary = null;
    #[ORM\Column(length: 150, nullable: true)]
    public ?string $category = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $conditions = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $advantage = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $advantageHtml = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $special = null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $normal = null;

    #[ORM\Column(type: Types::JSON)]
    public array $requires;

    // only used during YAML import
    //public List<String> requiresRef;
    // only used in specific view
    //public int depth;


    public function __toString(): string
    {
        return (string) $this->name;
    }
}
