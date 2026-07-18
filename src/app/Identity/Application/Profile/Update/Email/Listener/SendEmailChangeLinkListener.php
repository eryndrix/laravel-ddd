<?php declare(strict_types=1);

namespace App\Identity\Application\Profile\Update\Email\Listener;

use App\Shared\Application\Listener;
use App\Identity\Domain\Email\EmailChanged;
use App\Identity\Domain\Access\Auth\UserProviderInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;

final class SendEmailChangeLinkListener extends Listener
{
    /**
     * @phpstan-param UserProviderInterface $provider
     */
    public function __construct(
        private UserProviderInterface $provider
    ) {}

    /**
     * @phpstan-param EmailChanged $event
     * @phpstan-return void
     */
    public function handle(EmailChanged $event): void
    {
        $user = $this->provider->retrieveById(
            identifier: (string) $event->userId
        );

        if (!$user instanceof MustVerifyEmail) return;

        $user->sendEmailVerificationNotification();
    }
}
