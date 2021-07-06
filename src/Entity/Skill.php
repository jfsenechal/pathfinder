<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use AfmLibre\Pathfinder\Repository\SkillRepository;

/**
 * Competence
 * @ORM\Entity(repositoryClass=SkillRepository::class)
 */
class Skill
{
    use IdTrait;

    /**
     * @var
     */
    private $ability;
    /**
     * @var
     */
    private $training;
    /**
     * @var
     */
    private $armorpenalty;

    public function getId(): ?int
    {
        return $this->id;
    }
}
