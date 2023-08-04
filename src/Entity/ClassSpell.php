<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Classes\Repository\ClassSpellRepository;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;

#[ORM\Table(name: 'class_spell')]
#[ORM\UniqueConstraint(columns: ['class_t_id', 'spell_id'])]
#[ORM\Entity(repositoryClass: ClassSpellRepository::class)]
class ClassSpell
{
    use IdTrait;

    #[ORM\ManyToOne(targetEntity: Spell::class, inversedBy: 'class_spells')]
    #[ORM\JoinColumn(nullable: false)]
    public ?Spell $spell;

    #[ORM\ManyToOne(targetEntity: ClassT::class)]
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
