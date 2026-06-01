<?php declare(strict_types=1);

namespace App\Identity\Domain\Access\Jwt;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Identity\Domain\TokenHash;

interface JwtTokenManagerInterface
{
    /**
     * @phpstan-param Authenticatable $user
     * @phpstan-return string
     */
    public function issueToken(Authenticatable $user): string;
    
    /**
     * @phpstan-param TokenHash $token
     * @phpstan-return bool
     */
    public function isTokenValid(#[\SensitiveParameter] TokenHash $token): bool;

    /**
     * @phpstan-return void
     */
    public function invalidateToken(): void;

    /**
     * @phpstan-param Authenticatable $user
     * @phpstan-param int $ttl
     * 
     * @phpstan-return string
     */
    public function issueRefreshJwt(Authenticatable $user, int $ttl): string;
}
