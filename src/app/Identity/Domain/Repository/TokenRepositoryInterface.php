<?php declare(strict_types=1);

namespace App\Identity\Domain\Repository;

use App\Shared\Domain\Id\UserId;
use App\Shared\Domain\Id\TokenId;
use App\Identity\Domain\Token;
use App\Identity\Domain\TokenHash;

interface TokenRepositoryInterface
{
    /**
     * @phpstan-param UserId $userId
     * @phpstan-return Token[]
     */
    public function allByUserId(UserId $userId): array;

    /**
     * @phpstan-param \DateTimeImmutable $now
     * @phpstan-return Token[]
     */
    public function allExpired(\DateTimeImmutable $now): array;

    /**
     * @phpstan-param TokenHash $tokenHash
     * @phpstan-return Token|null
     */
    public function findByToken(TokenHash $tokenHash): ?Token;

    /**
     * @phpstan-param TokenId $tokenId
     * @phpstan-return Token|null
     */
    public function findById(TokenId $tokenId): ?Token;
    
    /**
     * @phpstan-param Token $token
     * @phpstan-return void
     */
    public function save(Token $token): void;
    
    /**
     * @phpstan-param Token $token
     * @phpstan-return void
     */
    public function remove(Token $token): void;
}
