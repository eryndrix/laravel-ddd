<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login\Output;

enum LoginError: string
{
    /**
     * Too many login attempts.
     */
    case TooManyAttempts = 'auth.login.too_many_attempts';

    /**
     * Invalid credentials provided.
     */
    case InvalidCredentials = 'auth.login.invalid_credentials';
    
    /**
     * Login failed due to system error.
     */
    case SystemError = 'auth.login.failed';

    /**
     * Unexpected error.
     */
    case UnexpectedError = 'common.unexpected_error';

    /**
     * @phpstan-return string
     */
    public function message(): string
    {
        $message = __(key: $this->value);
        return is_string(value: $message) ? $message : '';
    }
}
