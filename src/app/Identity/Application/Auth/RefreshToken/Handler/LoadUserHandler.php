<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\RefreshToken\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Auth\RefreshToken\RefreshTokenCommand;
use App\Identity\Domain\Access\Auth\UserProviderInterface;

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
     * @throws \LogicException
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
            throw new \LogicException(
                message: 'User not found.'
            );
        }

        $command->user = $user;

        return $next($command);
    }
}
