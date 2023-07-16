<?php


namespace AfmLibre\Pathfinder\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

trait NameTrait
{
    #[ORM\Column(type: 'string', length: 150, nullable: false)]
    protected ?string $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

}
