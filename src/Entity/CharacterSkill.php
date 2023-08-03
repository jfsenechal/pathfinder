<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Repository\CharacterSkillRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table]
#[ORM\UniqueConstraint(columns: ['character_id', 'skill_id'])]
#[ORM\Entity(repositoryClass: CharacterSkillRepository::class)]
class CharacterSkill
{
    use IdTrait;

    #[ORM\ManyToOne(targetEntity: Character::class)]
    #[ORM\JoinColumn(name: 'character_id', nullable: false)]
    public ?Character $character;

    #[ORM\ManyToOne(targetEntity: Skill::class)]
    #[ORM\JoinColumn(nullable: false)]
    public ?Skill $skill;

    #[ORM\Column(nullable: false)]
    public int $point_spent = 0;

    public function __construct(Character $character, Skill $skill)
    {
        $this->character = $character;
        $this->skill = $skill;
    }

}
