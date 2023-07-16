<?php


namespace AfmLibre\Pathfinder\Level;


use AfmLibre\Pathfinder\Entity\CharacterClass;

class LevelDto
{

    public function __construct(public CharacterClass $characterClass, public int $level)
    {
    }
}
