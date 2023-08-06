<?php

namespace AfmLibre\Pathfinder\Twig\Extension;

use AfmLibre\Pathfinder\Helper\NumberHelper;
use AfmLibre\Pathfinder\Twig\Runtime\PathfinderExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PathfinderExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('class_name', [PathfinderExtensionRuntime::class, 'className']),
            new TwigFilter('number_sign', fn($number) => NumberHelper::numberSign($number)),
        ];
    }

    public function getFunctions(): array
    {
        return [

        ];
    }
}
