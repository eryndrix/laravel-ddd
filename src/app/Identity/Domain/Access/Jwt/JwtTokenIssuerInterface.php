<?php declare(strict_types=1);

namespace App\Identity\Domain\Access\Jwt;

use Illuminate\Contracts\Auth\Authenticatable;

interface JwtTokenIssuerInterface
{
    /**
     * @phpstan-param Authenticatable $user
     * @phpstan-return array{
     *     access_token: string,
     *     access_token_ttl: int,
     *     refresh_token: string,
     *     refresh_token_ttl: int
     * }
     */
    public function issueTokensFor(Authenticatable $user): array;
}
