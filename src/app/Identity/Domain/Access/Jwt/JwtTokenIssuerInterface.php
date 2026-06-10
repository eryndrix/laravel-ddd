<?php declare(strict_types=1);

namespace App\Identity\Domain\Access\Jwt;

use Illuminate\Contracts\Auth\Authenticatable;

interface JwtTokenIssuerInterface
{
    /**
     * @phpstan-param Authenticatable $user
     * @phpstan-return array{
     *     access_token: string,
     *     ttl: int,
     *     refresh_token: string,
     *     refresh_ttl: \DateTimeImmutable
     * }
     */
    public function issueTokensFor(Authenticatable $user): array;
}
