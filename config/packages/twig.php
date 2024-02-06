<?php

use Symfony\Config\TwigConfig;

return static function (TwigConfig $twig) {
    $twig
        ->path('%kernel.project_dir%/src/AfmLibre/Pathfinder/templates', 'AfmLibrePathfinder')
        ->formThemes(
            ['bootstrap_5_layout.html.twig']
        );
};
