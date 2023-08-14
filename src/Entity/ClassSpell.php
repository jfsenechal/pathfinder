<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Classes\Repository\ClassSpellRepository;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;

#[ORM\Table(name: 'class_spell')]
#[ORM\UniqueConstraint(columns: ['class_t_id', 'spell_id'])]
#[ORM\Entity(repositoryClass: ClassSpellRepository::class)]
class ClassSpell
{
    use IdTrait;

    public function __construct(#[ORM\ManyToOne(targetEntity: Spell::class, inversedBy: 'class_spells')]
        #[ORM\JoinColumn(nullable: false)]
        public ?Spell $spell, #[ORM\ManyToOne(targetEntity: ClassT::class)]
        #[ORM\JoinColumn(nullable: false)]
        public ?ClassT $classT, #[Column(type: Types::SMALLINT)]
        public int $level)
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
