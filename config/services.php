<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $services = $containerConfigurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->private();

    $services->load('AfmLibre\Pathfinder\\', __DIR__ . '/../src/*')
        ->exclude([__DIR__ . '/../src/{Entity,Tests2}']);
};
