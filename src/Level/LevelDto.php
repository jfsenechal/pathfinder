<?php


namespace AfmLibre\Pathfinder\Level;


use AfmLibre\Pathfinder\Entity\CharacterClass;

class LevelDto
{
    public CharacterClass $characterClass;
    public int $level;

    public function __construct(CharacterClass $characterClass, int $level)
    {
        $this->level = $level;
        $this->characterClass = $characterClass;
    }
}
