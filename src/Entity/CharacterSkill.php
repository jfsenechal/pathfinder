<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Character\Repository\CharacterSkillRepository;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\UniqueConstraint(columns: ['character_id', 'skill_id'])]
#[ORM\Entity(repositoryClass: CharacterSkillRepository::class)]
class CharacterSkill
{
    use IdTrait;

    #[ORM\Column]
    public int $point_spent = 0;

    public function __construct(#[ORM\ManyToOne(targetEntity: Character::class)]
    #[ORM\JoinColumn(name: 'character_id', nullable: false)]
    public ?Character $character, #[ORM\ManyToOne(targetEntity: Skill::class)]
    #[ORM\JoinColumn(nullable: false)]
    public ?Skill $skill)
    {
    }

}
