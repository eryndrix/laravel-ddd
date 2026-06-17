<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\RefreshToken;

enum RefreshTokenError: string
{
    /**
     * Invalid refresh token format provided.
     */
    case InvalidTokenFormat = 'identity.refresh_token.invalid_format';

    /**
     * Refresh token does not exist.
     */
    case TokenNotExists = 'identity.refresh_token.not_exists';

    /**
     * Refresh token has been revoked (already used).
     */
    case TokenRevoked = 'identity.refresh_token.revoked';

    /**
     * Refresh token has expired.
     */
    case TokenExpired = 'identity.refresh_token.expired';

    /**
     * Token missing required ability to refresh.
     */
    case MissingAbility = 'identity.refresh_token.missing_ability';

    /**
     * Unknown error during token refresh.
     */
    case Unknown = 'identity.refresh_token.failed';
    
    /**
     * @phpstan-return string
     */
    public function message(): string
    {
        $message = __(key: $this->value);
        return is_string(value: $message) ? $message : '';
    }
}
