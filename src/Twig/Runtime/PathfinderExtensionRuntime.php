<?php

namespace AfmLibre\Pathfinder\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class PathfinderExtensionRuntime implements RuntimeExtensionInterface
{
    public function className($value)
    {
        return urldecode($value::class);
    }

    public function numberSign(int $value): string
    {
        if ($value > 0) {
            return '+'.$value;
        }

        return (string)$value;

    }
}
