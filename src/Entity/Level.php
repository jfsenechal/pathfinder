<?php

namespace AfmLibre\Pathfinder\Entity;

use Stringable;
use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use AfmLibre\Pathfinder\Level\Repository\LevelRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LevelRepository::class)]
class Level implements Stringable
{
    use IdTrait;

    #[ORM\Column]
    public int $lvl = 0;

    #[ORM\Column]
    public int $bab = 0; // base attack bonus

    #[ORM\Column]
    public int $fortitude = 0;

    #[ORM\Column]
    public int $reflex = 0;

    #[ORM\Column]
    public int $will = 0;

    #[ORM\Column]
    public int $maxSpellLvl = 0;

    /**
     * @var ClassFeature[] $features
     */
    public array $features = [];

    public function __construct(#[ORM\ManyToOne(targetEntity: ClassT::class)]
    public ?ClassT $classT)
    {
    }

    public function __toString(): string
    {
        return 'Level ' . $this->lvl;
    }
}
