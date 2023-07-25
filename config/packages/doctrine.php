<?php


use Symfony\Config\DoctrineConfig;

return static function (DoctrineConfig $doctrine) {
    $emMda = $doctrine->orm()->entityManager('default');
    $emMda->mapping('AfmLibrePathfinder')
        ->isBundle(false)
        ->type('attribute')
        ->dir('%kernel.project_dir%/src/AfmLibre/Pathfinder/src/Entity')
        ->prefix('AfmLibre\Pathfinder')
        ->alias('AfmLibrePathfinder');
};
