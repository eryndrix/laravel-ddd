<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token;

enum RefreshTokenError: string
{
    case InvalidToken = 'invalid_token';
    case Expired = 'expired';
    case MissingAbility = 'missing_ability';
    
    /**
     * Unknown error during login.
     */
    case Unknown = 'auth.login.failed';
    
    /**
     * @phpstan-return string
     */
    public function message(): string
    {
        $message = __(key: $this->value);
        return is_string(value: $message) ? $message : '';
    }
}
