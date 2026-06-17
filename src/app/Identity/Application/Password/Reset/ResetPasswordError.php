<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Reset;

enum ResetPasswordError: string
{
    /**
     * User not found or invalid token.
     */
    case InvalidEmail = 'identity.password.reset.invalid_email';

    /**
     * Password validation failed.
     */
    case InvalidPwdFormat = 'identity.password.reset.invalid_format';

    /**
     * Unknown error during password reset.
     */
    case Unknown = 'identity.password.reset.failed';
    
    /**
     * @phpstan-return string
     */
    public function message(): string
    {
        $message = __(key: $this->value);
        return is_string(value: $message) ? $message : '';
    }
}
