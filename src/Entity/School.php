<?php


namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use Doctrine\ORM\Mapping as ORM;
use AfmLibre\Pathfinder\Repository\SchoolRepository;

/**
 * @ORM\Entity(repositoryClass=SchoolRepository::class)
 */
class School
{
    use IdTrait;
    use NameTrait;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return $this->name;
    }
}
