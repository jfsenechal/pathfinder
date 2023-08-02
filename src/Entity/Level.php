<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Repository\LevelRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LevelRepository::class)]
class Level
{
    use IdTrait;

    #[ORM\Column(nullable: false)]
    public int $lvl = 0;

    #[ORM\Column(nullable: false)]
    public int $bab = 0; // base attack bonus

    #[ORM\Column(nullable: false)]
    public int $fortitude = 0;

    #[ORM\Column(nullable: false)]
    public int $reflex = 0;

    #[ORM\Column(nullable: false)]
    public int $will = 0;

    #[ORM\Column(nullable: false)]
    public int $maxSpellLvl = 0;

    #[ORM\ManyToOne(targetEntity: ClassT::class)]
    public ?ClassT $classT;

    /**
     * @var ClassFeature[] $features
     */
    public array $features = [];

    public function __construct(ClassT $classT)
    {
        $this->classT = $classT;
    }

    public function __toString(): string
    {
        return 'Level ' . $this->lvl;
    }
}
