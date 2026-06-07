<?php

return [
    /*
     * Core libraries and tooling
     */
    \Eryndrix\Doctrine\DoctrineConnector::class,
    \App\Shared\Infrastructure\DoctrinePersistenceBinder::class,
    \App\Shared\Infrastructure\BusServiceProvider::class,
    \Eryndrix\Telescope\TelescopeServiceProvider::class,

    /*
     * Identity module bindings
     */
    App\Identity\Infrastructure\IdentityRepositoryRegistrar::class,
    App\Identity\Infrastructure\Auth\AuthServiceProvider::class,
    App\Identity\Infrastructure\Jwt\JwtSecurityBootstrapper::class,
    App\Identity\Infrastructure\Dispatching\IdentityCommandDispatcher::class,
    App\Identity\Infrastructure\Dispatching\IdentityQueryDispatcher::class,
    App\Identity\Infrastructure\PasswordResetUrl::class,

    /*
     * Privilege module bindings
     */
    App\Privilege\Infrastructure\PrivilegeRepositoryRegistrar::class,
    App\Privilege\Infrastructure\Dispatching\PrivilegeQueryDispatcher::class,
    App\Privilege\Infrastructure\PrivilegeRouteProvider::class,
];
