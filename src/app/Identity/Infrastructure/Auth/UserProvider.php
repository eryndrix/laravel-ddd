<?php declare(strict_types=1);

namespace App\Identity\Infrastructure\Auth;

use App\Identity\Domain\User;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Identity\Domain\Access\Auth\UserProviderInterface;
use App\Identity\Domain\TokenHash;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Domain\Password\Password;
use App\Shared\Domain\Id\UserId;
use App\Shared\Domain\Email\Email;

final class UserProvider implements UserProviderInterface
{
    /**
     * @phpstan-param UserRepositoryInterface $repository
     */
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    /**
     * @phpstan-param mixed $identifier
     * @phpstan-return Authenticatable|null
     */
    public function retrieveById($identifier): ?Authenticatable
    {
        if (!is_string(value: $identifier)) {
            return null;
        }

        $user = $this->repository->findById(
            id: UserId::of(value: $identifier),
        );

        return $user instanceof User
            ? new UserAdapter(user: $user)
            : null;
    }

    /**
     * @phpstan-param mixed $identifier
     * @phpstan-param string $token
     * 
     * @phpstan-return Authenticatable|null
     */
    public function retrieveByToken(
        $identifier, #[\SensitiveParameter] $token): ?Authenticatable
    {
        if (!is_string(value: $identifier)) {
            return null;
        }

        $user = $this->repository->findByToken(
            id: UserId::of(value: $identifier),
            token: TokenHash::of(value: $token),
        );

        return $user instanceof User
            ? new UserAdapter(user: $user)
            : null;
    }

    /**
     * @phpstan-param Authenticatable $user
     * @phpstan-param string $token
     */
    public function updateRememberToken(
        Authenticatable $user, #[\SensitiveParameter] $token): void
    {
        if (!$user instanceof UserAdapter) {
            return;
        }

        $user->unwrap()->changeRememberToken(
            rememberToken: $token
        );

        $this->repository->save(user: $user->unwrap());
    }

    /**
     * @phpstan-return Authenticatable|null
     * @phpstan-ignore missingType.iterableValue
     */
    public function retrieveByCredentials(
        #[\SensitiveParameter] array $credentials): ?Authenticatable
    {
        /** @phpstan-var array<string, mixed> $credentials */
        $email = data_get(
            target: $credentials,
            key: 'email'
        );

        if (!is_string(value: $email)) {
            return null;
        }

        $user = $this->repository->findByEmail(
            email: Email::of(value: $email),
        );

        return $user instanceof User
            ? new UserAdapter(user: $user)
            : null;
    }

    /**
     * @phpstan-param Authenticatable $user
     * @phpstan-return bool
     * @phpstan-ignore missingType.iterableValue
     */
    public function validateCredentials(
        Authenticatable $user,
        #[\SensitiveParameter] array $credentials): bool
    {
        if (!$user instanceof UserAdapter) {
            return false;
        }

        /** @phpstan-var array<string, mixed> $credentials */
        $plainPassword = data_get(
            target: $credentials,
            key: 'password'
        );

        if (!is_string(value: $plainPassword)) {
            return false;
        }
        
        return $user->unwrap()->password->verify(
            password: $plainPassword
        );
    }

    /**
     * @phpstan-param Authenticatable $user
     * @phpstan-param bool $force
     * 
     * @phpstan-return bool
     * @phpstan-ignore missingType.iterableValue
     */
    public function rehashPasswordIfRequired(
        Authenticatable $user,
        #[\SensitiveParameter] array $credentials,
        bool $force = false): bool
    {
        if (!$user instanceof UserAdapter) {
            return false;
        }

        /** @phpstan-var array<string, mixed> $credentials */
        $plainPassword = $credentials['password'] ?? null;

        if (!is_string(value: $plainPassword)) {
            return false;
        }

        $password = $user->getAuthPassword();
        
        $hashedPassword = new Password(hash: $password);
        
        if (!$hashedPassword->needsRehash() || !$force) {
            return false;
        }

        $newHashedPassword = Password::fromPlain(
            value: $plainPassword
        );

        $user->unwrap()->changePassword(
            password: $newHashedPassword
        );

        $this->repository->save(user: $user->unwrap());

        return true;
    }
}
