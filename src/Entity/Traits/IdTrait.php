<?php


namespace AfmLibre\Pathfinder\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

trait IdTrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public ?int $id;

    public function getId(): ?int
    {
        return $this->id;
    }

}
