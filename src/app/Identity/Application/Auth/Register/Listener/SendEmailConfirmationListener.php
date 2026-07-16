<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Register\Listener;

use App\Shared\Application\Listener;
use App\Identity\Domain\Register\UserRegistered;
use App\Identity\Domain\Access\Auth\UserProviderInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;

final class SendEmailConfirmationListener extends Listener
{
    /**
     * @phpstan-param UserProviderInterface $provider
     */
    public function __construct(
        private UserProviderInterface $provider
    ) {}

    /**
     * @phpstan-param UserRegistered $event
     * @phpstan-return void
     */
    public function handle(UserRegistered $event): void
    {
        $user = $this->provider->retrieveById(
            identifier: (string) $event->userId
        );

        if (!$user instanceof MustVerifyEmail) return;

        $user->sendEmailVerificationNotification();
    }
}
