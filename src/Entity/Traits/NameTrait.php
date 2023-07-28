<?php


namespace AfmLibre\Pathfinder\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait NameTrait
{
    #[ORM\Column(length: 150, nullable: false)]
    public ?string $name;
}
