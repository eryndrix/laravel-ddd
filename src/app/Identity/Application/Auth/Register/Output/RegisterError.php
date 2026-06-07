<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Register\Output;

enum RegisterError: string
{
    /**
     * Registration failed due to system error.
     */
    case SystemError = 'auth.registration.failed';

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
