<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Forgot;

enum ForgotPasswordError: string
{
    /**
     * Too many password reset requests.
     */
    case Throttled = 'password.forgot.throttled';

    /**
     * Unknown error during password reset.
     */
    case Unknown = 'password.forgot.failed';
    
    /**
     * @phpstan-return string
     */
    public function message(): string
    {
        $message = __(key: $this->value);
        return is_string(value: $message) ? $message : '';
    }
}
