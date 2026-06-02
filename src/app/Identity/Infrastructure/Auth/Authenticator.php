<?php declare(strict_types=1);

namespace App\Identity\Infrastructure\Auth;

use App\Identity\Domain\Access\Auth\UserProviderInterface;
use App\Identity\Domain\Access\Auth\AuthenticatorInterface;
use Illuminate\Contracts\Auth\Authenticatable;

final class Authenticator implements AuthenticatorInterface
{
    /**
     * @param UserProviderInterface $userProvider
     */
    public function __construct(
        private UserProviderInterface $userProvider
    ) {}

    /**
     * @phpstan-param array<string, mixed> $credentials
     * @phpstan-return Authenticatable
     *
     * @throws \InvalidArgumentException
     */
    public function authenticate(
        array $credentials): Authenticatable
    {
        $user = $this->userProvider->retrieveByCredentials(
            $credentials
        );

        if (!$user instanceof Authenticatable) {
            throw new \InvalidArgumentException(
                message: 'Invalid credentials.'
            );
        }

        if (!$this->userProvider->validateCredentials(
            user: $user,
            credentials: $credentials
        )) {
            throw new \InvalidArgumentException(
                message: 'Invalid credentials.'
            );
        }

        $this->userProvider->rehashPasswordIfRequired(
            user: $user,
            credentials: $credentials
        );

        return $user;
    }
}
