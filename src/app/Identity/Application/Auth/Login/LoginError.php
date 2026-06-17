<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login;

enum LoginError: string
{
    /**
     * Invalid credentials provided.
     */
    case InvalidCredentials = 'identity.login.invalid_credentials';
    
    /**
     * Too many login attempts.
     */
    case TooManyAttempts = 'identity.login.too_many_attempts';

    /**
     * Unknown error during login.
     */
    case Unknown = 'identity.login.failed';
    
    /**
     * @phpstan-return string
     */
    public function message(): string
    {
        $message = __(key: $this->value);
        return is_string(value: $message) ? $message : '';
    }
}
