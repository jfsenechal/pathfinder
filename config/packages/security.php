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
        'login_throttling' => [
            'max_attempts' => 6, //per minute...
        ],
        'remember_me' => [
            'secret' => '%kernel.secret%',
            'lifetime' => 604800,
            'path' => '/',
            'always_remember_me' => true,
        ],
    ];

    $security->firewall('main', $main);
};
