<?php


namespace AfmLibre\Pathfinder\Level;


use AfmLibre\Pathfinder\Entity\ClassT;

class LevelDto
{

    public function __construct(public ClassT $classT, public int $level)
    {
    }
}
