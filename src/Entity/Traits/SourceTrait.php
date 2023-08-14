<?php

namespace AfmLibre\Pathfinder\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait SourceTrait
{
    #[ORM\Column(length: 255, nullable: true)]
    public ?string $reference = null;

    #[ORM\Column(length: 150, nullable: true)]
    public ?string $sourced;
}
