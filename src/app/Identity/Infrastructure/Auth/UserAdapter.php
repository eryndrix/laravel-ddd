<?php declare(strict_types=1);

namespace App\Identity\Infrastructure\Auth;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use App\Identity\Domain\User;

final class UserAdapter implements
    AuthenticatableContract,
    JWTSubject,
    CanResetPasswordContract
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
     * @phpstan-param User $user
     */
    public function __construct(
        public private(set) User $user,
    ) {}

    /**
     * @phpstan-return string
     */
    public function getJWTIdentifier(): string
    {
        return $this->user->id->value();
    }

    /**
     * @phpstan-return array<string, mixed>
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'id' => $this->user->id->value(),
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
     * @phpstan-return string The user's email.
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
