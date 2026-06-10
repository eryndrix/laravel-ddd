<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Auth\Login\LoginCommand;
use App\Identity\Domain\Access\Auth\AuthenticatorInterface;
use App\Identity\Application\Auth\Login\LoginError;
use App\Shared\Application\Handler\HandlerException;
use App\Shared\Application\Result\Result;

final class AuthenticateUserHandler extends Handler
{
    /**
     * @phpstan-param AuthenticatorInterface $authenticator
     */
    public function __construct(
        private AuthenticatorInterface $authenticator
    ) {}

    /**
     * @phpstan-param LoginCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws HandlerException<LoginError>
     */
    public function handle(
        LoginCommand $command, \Closure $next): mixed
    {
        /** @phpstan-var array<string, mixed> $credentials */
        $credentials = $command->toArray();

        $user = $this->authenticator->authenticate(
            credentials: $credentials
        );

        if (is_null(value: $user)) {
            throw new HandlerException(
                error: LoginError::InvalidCredentials
            );
        }

        $command->user = $user;

        return $next($command);
    }
}
