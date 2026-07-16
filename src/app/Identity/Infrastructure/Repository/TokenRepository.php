<?php declare(strict_types=1);

namespace App\Identity\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use App\Identity\Domain\Repository\TokenRepositoryInterface;
use App\Identity\Domain\Token;
use App\Identity\Domain\Access\TokenHash;
use App\Shared\Domain\Id\{TokenId, UserId};

final class TokenRepository implements TokenRepositoryInterface
{
    /**
     * @phpstan-param EntityManagerInterface $entityManager
     */
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @phpstan-param UserId $userId
     * @phpstan-return Token[]
     */
    public function allByUserId(UserId $userId): array
    {
        $qb = $this->entityManager->createQueryBuilder();

        /** @phpstan-var Token[] $tokens */
        $tokens = $qb->select('pat')
            ->from(from: Token::class,  alias: 'pat')
            ->where('pat.tokenableId = :userId')
            ->setParameter(key: 'userId', value: $userId)
            ->getQuery()
            ->getResult();

        return $tokens;
    }

    /**
     * @phpstan-param \DateTimeImmutable $now
     * @phpstan-return Token[]
     */
    public function allExpired(\DateTimeImmutable $now): array
    {
        $qb = $this->entityManager->createQueryBuilder();

        /** @phpstan-var Token[] $tokens */
        $tokens = $qb->select('pat')
            ->from(from: Token::class, alias: 'pat')
            ->where('pat.expiresAt IS NOT NULL')
            ->andWhere('pat.expiresAt < :now')
            ->setParameter(key: 'now', value: $now)
            ->getQuery()
            ->getResult();

        return $tokens;
    }

    /**
     * @phpstan-param TokenHash $tokenHash
     * @phpstan-return Token|null
     */
    public function findByToken(TokenHash $tokenHash): ?Token
    {
        $qb = $this->entityManager->createQueryBuilder();

        /** @phpstan-var Token|null $token */
        $token = $qb->select('pat')
            ->from(from: Token::class, alias: 'pat')
            ->where('pat.token.tokenHash = :tokenHash')
            ->setParameter(key: 'tokenHash', value: (string) $tokenHash)
            ->getQuery()
            ->getOneOrNullResult();

        return $token;
    }

    /**
     * @phpstan-param TokenId $tokenId
     * @phpstan-return Token|null
     */
    public function findById(TokenId $tokenId): ?Token
    {
        $qb = $this->entityManager->createQueryBuilder();

        /** @phpstan-var Token|null $token */
        $token = $qb->select('pat')
            ->from(from: Token::class, alias: 'pat')
            ->where('pat.id = :tokenId')
            ->setParameter(key: 'tokenId', value: $tokenId)
            ->getQuery()
            ->getOneOrNullResult();

        return $token;
    }

    /**
     * @phpstan-param Token $token
     * @phpstan-return void
     */
    public function save(Token $token): void
    {
        $this->entityManager->persist(object: $token);
    }

    /**
     * @phpstan-param Token $token
     * @phpstan-return void
     */
    public function remove(Token $token): void
    {
        $this->entityManager->remove(object: $token);
    }
}
