<?php declare(strict_types=1);

namespace App\Identity\Infrastructure\Jwt;

use App\Identity\Domain\Access\Jwt\JwtTokenManagerInterface;
use App\Identity\Domain\Access\Jwt\JwtTokenIssuerInterface;
use Illuminate\Contracts\Auth\Authenticatable;

final class JwtTokenIssuer implements JwtTokenIssuerInterface
{
    /**
     * @phpstan-param JwtTokenManagerInterface $jwtTokenManager
     */
    public function __construct(
        private JwtTokenManagerInterface $jwtTokenManager
    ) {}

    /**
     * @phpstan-param Authenticatable $user
     * @phpstan-return array{
     *     access_token: string,
     *     access_token_ttl: int,
     *     refresh_token: string,
     *     refresh_token_ttl: int
     * }
     * 
     * @throws \RuntimeException
     */
    public function issueTokensFor(Authenticatable $user): array
    {
        $accessToken = $this->jwtTokenManager->issueToken(
            user: $user
        );

        $rTtl = config(key: 'jwt.refresh_ttl');

        if (!is_int(value: $rTtl)) {
            throw new \RuntimeException(
                message: 'Invalid refresh_ttl configuration.'
            );
        }

        $refreshToken = $this->jwtTokenManager->issueRefreshJwt(
            user: $user,
            ttl: $rTtl
        );

        if ($refreshToken === '') {
            throw new \RuntimeException(
                 message: 'Failed to issue new refresh token.'
            );
        }

        $aTtl = config(key: 'jwt.ttl');

        if (!is_int(value: $aTtl)) {
            throw new \RuntimeException(
                message: 'Invalid ttl configuration.'
            );
        }

        return [
            'access_token' => $accessToken,
            'access_token_ttl' => $aTtl,
            'refresh_token' => $refreshToken,
            'refresh_token_ttl' => $rTtl
        ];
    }
}
