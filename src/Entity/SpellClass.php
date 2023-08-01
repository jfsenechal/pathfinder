<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Repository\SpellClassRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;

#[ORM\Table(name: 'spell_class')]
#[ORM\UniqueConstraint(columns: ['class_t_id', 'spell_id'])]
#[ORM\Entity(repositoryClass: SpellClassRepository::class)]
class SpellClass
{
    use IdTrait;

    #[ORM\ManyToOne(targetEntity: Spell::class, inversedBy: 'spell_classes')]
    #[ORM\JoinColumn(nullable: false)]
    public ?Spell $spell;

    #[ORM\ManyToOne(targetEntity: ClassT::class, inversedBy: 'spell_classes')]
    #[ORM\JoinColumn(nullable: false)]
    public ?ClassT $classT;

    #[Column(type: 'smallint')]
    public int $level = 0;

    public function __construct(Spell $spell, ClassT $classT, int $level)
    {
        $this->spell = $spell;
        $this->classT = $classT;
        $this->level = $level;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
