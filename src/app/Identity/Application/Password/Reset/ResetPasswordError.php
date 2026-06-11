<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Reset;

enum ResetPasswordError: string
{
    /**
     * User not found or invalid token.
     */
    case Invalid = 'password.reset.invalid';

    /**
     * Password validation failed.
     */
    case WeakPassword = 'password.reset.weak';

    /**
     * Unknown error during password reset.
     */
    case Unknown = 'password.reset.failed';
    
    /**
     * @phpstan-return string
     */
    public function message(): string
    {
        $message = __(key: $this->value);
        return is_string(value: $message) ? $message : '';
    }
}
