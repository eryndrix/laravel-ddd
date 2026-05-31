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
     * Privilege module bindings
     */
    App\Privilege\Infrastructure\PrivilegeRepositoryRegistrar::class,
    App\Privilege\Infrastructure\Dispatching\PrivilegeQueryDispatcher::class,
    App\Privilege\Infrastructure\RouteServiceProvider::class,
];
