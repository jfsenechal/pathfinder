<?php


namespace AfmLibre\Pathfinder\Entity\Traits;

trait SlugTrait
{
    public function getSluggableFields(): array
    {
        return ['name'];
    }
}
