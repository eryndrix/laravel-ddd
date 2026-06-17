<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Update\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Email\Update\UpdateEmailCommand;

final class SendVerificationEmailHandler extends Handler
{
    /**
     * @phpstan-param UpdateEmailCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     */
    public function handle(
        UpdateEmailCommand $command, \Closure $next): mixed
    {
        /** @phpstan-var \App\Identity\Infrastructure\Auth\UserAdapter $user */
        $user = $command->user;
        $user->sendEmailVerificationNotification();

        return $next($command);
    }
}
