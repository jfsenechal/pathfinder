<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('twig', ['form_themes' => ['bootstrap_5_layout.html.twig']]);

    $containerConfigurator->extension(
        'twig',
        [
            'paths' => [
                '%kernel.project_dir%/src/AfmLibre/Pathfinder/templates' => 'AfmLibrePathfinder',
            ],
        ]
    );
};
