<?php

namespace AfmLibre\Pathfinder\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class PathfinderExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function doSomething($value)
    {
        return urldecode($value::class);
    }
}
