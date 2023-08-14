<?php

namespace AfmLibre\Pathfinder\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class PathfinderExtensionRuntime implements RuntimeExtensionInterface
{
    public function className(object $value)
    {
        return urldecode($value::class);
    }
}
