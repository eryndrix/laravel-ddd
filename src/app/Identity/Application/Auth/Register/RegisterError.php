<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Register;

enum RegisterError: string
{
    /**
     * Invalid email or password format provided.
     */
    case InvalidCredentialsFormat = 'identity.register.invalid_format';

    /**
     * Unknown error during registration.
     */
    case Unknown = 'identity.register.failed';
    
    /**
     * @phpstan-return string
     */
    public function message(): string
    {
        $message = __(key: $this->value);
        return is_string(value: $message) ? $message : '';
    }
}
