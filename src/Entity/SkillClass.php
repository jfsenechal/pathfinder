<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Repository\SkillClassRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'skill_class')]
#[ORM\UniqueConstraint(columns: ['class_t_id', 'skill_id'])]
#[ORM\Entity(repositoryClass: SKillClassRepository::class)]
class SkillClass
{
    use IdTrait;

    #[ORM\ManyToOne(targetEntity: Skill::class, inversedBy: 'skill_classes')]
    #[ORM\JoinColumn(nullable: false)]
    public Skill $skill;

    #[ORM\ManyToOne(targetEntity: ClassT::class, inversedBy: 'skill_classes')]
    #[ORM\JoinColumn(nullable: false)]
    public ClassT $classT;

    public function __construct(Skill $skill, ClassT $classT)
    {
        $this->classT = $classT;
        $this->skill = $skill;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
