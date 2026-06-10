<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Auth\Token\RefreshTokenCommand;
use App\Identity\Domain\Auth\UserProviderInterface;

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
     */
    public function handle(
        RefreshTokenCommand $command, \Closure $next): mixed
    {
        /** @phpstan-var \App\Identity\Domain\Token $oldToken */
        $oldToken = $command->oldToken;

        $user = $this->userProvider->retrieveById(
            identifier: $oldToken->tokenableId->value()
        );

        $command->user = $user;

        return $next($command);
    }
}
