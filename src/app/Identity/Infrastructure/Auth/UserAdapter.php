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
     * Create a new AuthUserAdapter instance.
     *
     * @param User $user
     */
    public function __construct(
        public private(set) User $user,
    ) {}

    /**
     * Get the identifier that will be stored in the JWT subject claim.
     *
     * @return string
     */
    public function getJWTIdentifier(): string
    {
        return $this->user->id->value();
    }

    /**
     * Get the custom claims to be added to the JWT.
     *
     * @return array<string, mixed>
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'id' => $this->user->id->value(),
            'email' => (string) $this->user->email,
        ];
    }

    /**
     * Returns the primary key name used by the model.
     *
     * @return string
     */
    public function getKeyName(): string
    {
        return 'id';
    }

    /**
     * Get the user's email address as string.
     *
     * @return string The user's email.
     */
    public function getEmail(): string
    {
        return $this->user->email->value();
    }

    /**
     * Get the "remember me" token value.
     *
     * @return string
     */
    public function getRememberToken(): string
    {
        return $this->user->rememberToken ?? '';
    }

    /**
     * Set the "remember me" token value.
     *
     * @param string|null $value
     */
    public function setRememberToken($value): void
    {
        $this->user->changeRememberToken(
            rememberToken: $value
        );
    }

    /**
     * Get the name of the "remember me" token column.
     *
     * @return string
     */
    public function getRememberTokenName(): string
    {
        return 'rememberToken';
    }

    /**
     * Unwraps and returns the underlying User domain object.
     *
     * @return User
     */
    public function unwrap(): User
    {
        return $this->user;
    }

    /**
     * Magic getter for accessing wrapped user properties.
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        // @phpstan-ignore-next-line
        return $this->user->$name;
    }
}
