<?php declare(strict_types=1);

namespace App\Identity\Domain\Creating;

use App\Identity\Domain\Token;
use App\Identity\Domain\Abilities;
use App\Identity\Domain\TokenHash;
use App\Identity\Domain\User;
use App\Shared\Domain\Id\UserId;

final class TokenCreator
{
    /**
     * @phpstan-param UserId $userId
     * @phpstan-param TokenHash $tokenHash
     * @phpstan-param \DateTimeImmutable $expiresAt
     * 
     * @phpstan-return Token
     */
    public static function newRefreshToken(
        UserId $userId,
        TokenHash $tokenHash,
        \DateTimeImmutable $expiresAt): Token
    {
        return new Token(
            tokenableType: User::class,
            tokenableId: $userId,
            name: 'refresh_token',
            token: $tokenHash,
            abilities: Abilities::fromArray(value: ['refresh']),
            expiresAt: $expiresAt
        );
    }
}
