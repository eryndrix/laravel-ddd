<?php declare(strict_types=1);

namespace App\Identity\Infrastructure;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;

final class IdentityEventsMapping extends EventServiceProvider
{
    /**
     * @phpstan-var array<string, array<int, string>>
     */
    protected $listen = [
        \App\Identity\Domain\Register\UserRegistered::class => [
            \App\Identity\Application\Auth\Register\Listener\SendEmailConfirmationListener::class
        ],
        \App\Identity\Domain\Email\EmailChanged::class => [
            \App\Identity\Application\Profile\Update\Email\Listener\SendEmailChangeLinkListener::class
        ],
    ];

    /**
     * @phpstan-return void
     */
    public function boot(): void
    {
        parent::boot();
    }
}
