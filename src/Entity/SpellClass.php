<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Repository\SpellClassRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;

#[ORM\Table(name: 'spell_class')]
#[ORM\Entity(repositoryClass: SpellClassRepository::class)]
class SpellClass
{
    use IdTrait;

    #[ORM\ManyToOne(targetEntity: Spell::class, inversedBy: 'spell_classes')]
    #[ORM\JoinColumn(nullable: false)]
    public ?Spell $spell;

    #[ORM\ManyToOne(targetEntity: CharacterClass::class, inversedBy: 'spell_classes')]
    #[ORM\JoinColumn(nullable: false)]
    public ?CharacterClass $characterClass;

    #[Column(type: 'smallint')]
    public int $level = 0;

    public function __construct(Spell $spell, CharacterClass $characterClass, int $level)
    {
        $this->spell = $spell;
        $this->characterClass = $characterClass;
        $this->level = $level;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
