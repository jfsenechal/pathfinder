<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Classes\Repository\ClassSkillRepository;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'class_skill')]
#[ORM\UniqueConstraint(columns: ['class_t_id', 'skill_id'])]
#[ORM\Entity(repositoryClass: ClassSkillRepository::class)]
class ClassSkill
{
    use IdTrait;

    #[ORM\ManyToOne(targetEntity: Skill::class)]
    #[ORM\JoinColumn(nullable: false)]
    public Skill $skill;

    #[ORM\ManyToOne(targetEntity: ClassT::class)]
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
