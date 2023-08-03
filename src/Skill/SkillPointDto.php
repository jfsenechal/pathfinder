<?php

namespace AfmLibre\Pathfinder\Skill;

class SkillPointDto
{
    public function __construct(
        public readonly int $class,
        public readonly int $intelligence,
        public readonly int $human,
        public readonly int $bonusIncrease,
        public readonly int $pointsSpent
    ) {
    }

    public function totalToSpend(): int
    {
        return $this->class + $this->intelligence + $this->human + $this->bonusIncrease;
    }

}