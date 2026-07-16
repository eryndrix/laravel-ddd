<?php declare(strict_types=1);

namespace App\Identity\Infrastructure\Auth;

use App\Identity\Domain\User;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Identity\Domain\Access\Auth\UserAdapterInterface;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Notifications\Notifiable;

final class UserAdapter implements
    JWTSubject,
    UserAdapterInterface
{
    /**
     * Enables password reset functionality for the user.
     */
    use CanResetPassword;

    /**
     * Enables notification sending capabilities for the user.
     */
    use Notifiable;

    /**
     * Provides authentication methods for the user.
     */
    use Authenticatable;

    /**
     * Provides email verification methods for the user.
     */
    use MustVerifyEmail;

    /**
     * @phpstan-param User $user
     */
    public function __construct(
        public private(set) User $user,
    ) {}

    /**
     * @phpstan-return string
     */
    public function getKey(): string
    {
        return $this->user->id->value();
    }

    /**
     * @phpstan-return string
     */
    public function getJWTIdentifier(): string
    {
        return $this->getKey();
    }

    /**
     * @phpstan-return array<string, mixed>
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'id' => $this->getKey(),
            'email' => (string) $this->user->email,
        ];
    }

    /**
     * @phpstan-return string
     */
    public function getKeyName(): string
    {
        return 'id';
    }

    /**
     * @phpstan-return string
     */
    public function getEmail(): string
    {
        return $this->user->email->value();
    }

    /**
     * @phpstan-param mixed $notification
     * @phpstan-return string
     */
    public function routeNotificationForMail($notification = null): string
    {
        return $this->getEmail();
    }

    /**
     * @phpstan-return string
     */
    public function getAuthPassword(): string
    {
        return $this->user->password->value();
    }

    /**
     * @phpstan-return string
     */
    public function getAuthPasswordName(): string
    {
        return 'password';
    }

    /**
     * @phpstan-return string
     */
    public function getRememberToken(): string
    {
        return $this->user->rememberToken ?? '';
    }

    /**
     * @phpstan-param string|null $value
     */
    public function setRememberToken($value): void
    {
        $this->user->changeRememberToken(
            rememberToken: $value
        );
    }

    /**
     * @phpstan-return string
     */
    public function getRememberTokenName(): string
    {
        return 'rememberToken';
    }

    /**
     * @phpstan-return User
     */
    public function unwrap(): User
    {
        return $this->user;
    }

    /**
     * @phpstan-param string $name
     * @phpstan-return mixed
     */
    public function __get(string $name)
    {
        // @phpstan-ignore-next-line
        return $this->user->$name;
    }
}
