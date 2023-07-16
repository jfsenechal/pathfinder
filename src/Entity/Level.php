<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use AfmLibre\Pathfinder\Repository\LevelRepository;

#[ORM\Entity(repositoryClass: LevelRepository::class)]
class Level
{
    use IdTrait;

    /**
     * @var int[]
     */
    private $bab; // base attack bonus
    /**
     * @var int
     */
    private $fortitude;
    /**
     * @var int
     */
    private $reflex;
    /**
     * @var int
     */
    private $will;
    /**
     * @var int
     */
    private $maxSpellLvl;

}
