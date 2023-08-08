<?php

use AfmLibre\Pathfinder\Modifiers\ModifiersHandler;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

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

 /*   $services->set(ModifiersHandler::class)
        // inject all services tagged with app.handler as first argument
        ->args([tagged_iterator('pathfinder.modifier_tag')])
    ;*/
};
