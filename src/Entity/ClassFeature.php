<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Entity\Traits\NameTrait;
use AfmLibre\Pathfinder\Repository\ClassFeatureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassFeatureRepository::class)]
class ClassFeature implements \Stringable
{
    use IdTrait;
    use NameTrait;

    #[\Doctrine\ORM\Mapping\Column(type: 'smallint')]
    private int $level;

    #[ORM\Column(type: 'text', nullable: true)]
    protected ?string $description = null;

    #[ORM\Column(type: 'string', length: 250, nullable: true)]
    protected ?string $reference = null;
    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    protected ?string $source = null;

    #[ORM\Column(type: 'boolean')]
    protected bool $auto = false;

    public function __construct(#[ORM\ManyToOne(targetEntity: CharacterClass::class)]
    protected ?CharacterClass $character_class)
    {
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getAuto(): ?bool
    {
        return $this->auto;
    }

    public function setAuto(bool $auto): self
    {
        $this->auto = $auto;

        return $this;
    }

    public function getCharacterClass(): ?CharacterClass
    {
        return $this->character_class;
    }

    public function setCharacterClass(?CharacterClass $character_class): self
    {
        $this->character_class = $character_class;

        return $this;
    }

}
