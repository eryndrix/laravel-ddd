<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login\Handler;

use App\Shared\Application\Handler;
use App\Identity\Domain\Access\Auth\AuthenticatorInterface;
use App\Identity\Application\Auth\Login\Exception\InvalidCredentialsException;
use App\Identity\Application\Auth\Login\LoginCommand;
use App\Identity\Domain\Access\Auth\UserAdapterInterface;

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
     * @phpstan-param \Closure(LoginCommand):mixed $next
     * 
     * @phpstan-return mixed
     *
     * @throws InvalidCredentialsException
     */
    public function handle(
        LoginCommand $command, \Closure $next): mixed
    {
        $user = $this->authenticator->authenticate(
            credentials: [
                'email' => $command->email,
                'password' => $command->password
            ]
        );

        if (is_null(value: $user)) {
            throw new InvalidCredentialsException();
        }

        /** @phpstan-var UserAdapterInterface $user */
        $command->user = $user;

        return $next($command);
    }
}
