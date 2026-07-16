<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token\Refresh\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Auth\Token\Refresh\RefreshTokenCommand;
use App\Identity\Domain\Access\Auth\UserProviderInterface;
use App\Shared\Application\Exception\UserNotFoundException;

final class LoadUserHandler extends Handler
{
    /**
     * @phpstan-param UserProviderInterface $userProvider
     */
    public function __construct(
        private UserProviderInterface $userProvider
    ) {}

    /**
     * @phpstan-param RefreshTokenCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws UserNotFoundException
     */
    public function handle(
        RefreshTokenCommand $command, \Closure $next): mixed
    {
        /** @phpstan-var \App\Identity\Domain\Token $oldToken */
        $oldToken = $command->oldToken;

        $user = $this->userProvider->retrieveById(
            identifier: $oldToken->tokenableId->value()
        );

        if (is_null(value: $user)) {
            throw new UserNotFoundException();
        }

        $command->user = $user;

        return $next($command);
    }
}
