<?php

use AfmLibre\Pathfinder\Entity\User;
use AfmLibre\Pathfinder\Security\PathfinderAuthenticator;
use Symfony\Config\SecurityConfig;

return static function (SecurityConfig $security) {

    $security->provider('pathfinder_user_provider', [
        'entity' => [
            'class' => User::class,
            'property' => 'email',
        ],
    ]);

    // @see Symfony\Config\Security\FirewallConfig
    $main = [
        'provider' => 'pathfinder_user_provider',
        'logout' => ['path' => 'app_logout'],
        'form_login' => [],
        'entry_point' => PathfinderAuthenticator::class,
        'custom_authenticators' => [PathfinderAuthenticator::class],
    ];

    $security->firewall('main', $main);
};
