<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension(
        'doctrine',
        [
            'orm' => [
                'mappings' => [
                    'AfmLibre\Pathfinder' => [
                        'is_bundle' => false,
                        'type' => 'annotation',
                        'dir' => '%kernel.project_dir%/src/AfmLibre/Pathfinder/src/Entity',
                        'prefix' => 'AfmLibre\Pathfinder',
                        'alias' => 'AfmLibre\Pathfinder',
                    ],
                ],
            ],
        ]
    );
};
