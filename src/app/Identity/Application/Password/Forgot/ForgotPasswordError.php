<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Forgot;

enum ForgotPasswordError: string
{
    /**
     * Too many password reset requests.
     */
    case TooManyAttempts = 'identity.password.forgot.too_many_attempts';

    /**
     * Invalid email format provided.
     */
    case InvalidEmailFormat = 'identity.password.forgot.invalid_email_format';

    /**
     * Email does not exist in the system.
     */
    case EmailNotExists = 'identity.password.forgot.email_not_exists';

    /**
     * Unknown error during password reset.
     */
    case Unknown = 'identity.password.forgot.failed';
    
    /**
     * @phpstan-return string
     */
    public function message(): string
    {
        $message = __(key: $this->value);
        return is_string(value: $message) ? $message : '';
    }
}
