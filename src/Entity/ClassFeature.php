<?php

namespace AfmLibre\Pathfinder\Entity;

use AfmLibre\Pathfinder\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use AfmLibre\Pathfinder\Repository\ClassFeatureRepository;

/**
 * @ORM\Entity(repositoryClass=ClassFeatureRepository::class)
 */
class ClassFeature
{
    use IdTrait;

    /**
     */
    private $conditions;
    /**
     */
    private $class_;
    /**
     */
    private $auto;
    /**
     */
    private $level;

}
