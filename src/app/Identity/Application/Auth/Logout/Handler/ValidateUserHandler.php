<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Logout\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Auth\Logout\LogoutCommand;
use App\Identity\Domain\User;

final class ValidateUserHandler extends Handler
{
    /**
     * @phpstan-param LogoutCommand $command
     * @phpstan-param \Closure $next
     * 
     * @throws \LogicException
     */
    public function handle(
        LogoutCommand $command, \Closure $next): mixed
    {
        /** @phpstan-var User $user */
        $user = $command->user;

        if (!$user instanceof User) {
            throw new \LogicException(
                message: 'User not found.'
            );
        }

        return $next($command);
    }
}
