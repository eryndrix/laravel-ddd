<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token;

enum RefreshTokenError: string
{
    /**
     * Invalid refresh token provided.
     */
    case InvalidToken = 'auth.token.invalid_token';

    /**
     * Refresh token has expired.
     */
    case Expired = 'auth.token.expired';

    /**
     * Token missing required ability to refresh.
     */
    case MissingAbility = 'auth.token.missing_ability';

    /**
     * Unknown error during token refresh.
     */
    case Unknown = 'auth.token.failed';
    
    /**
     * @phpstan-return string
     */
    public function message(): string
    {
        $message = __(key: $this->value);
        return is_string(value: $message) ? $message : '';
    }
}
