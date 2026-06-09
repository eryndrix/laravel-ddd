<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Register;

enum RegisterError: string
{
    /**
     * Invalid credentials provided.
     */
    case InvalidCredentials = 'auth.invalid_credentials';

    /**
     * Unknown error during registration.
     */
    case Unknown = 'auth.registration.failed';
    
    /**
     * @phpstan-return string
     */
    public function message(): string
    {
        $message = __(key: $this->value);
        return is_string(value: $message) ? $message : '';
    }
}
