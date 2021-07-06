<?php


namespace AfmLibre\Pathfinder;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AfmLibrePathfinderBundle extends Bundle
{
    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}
