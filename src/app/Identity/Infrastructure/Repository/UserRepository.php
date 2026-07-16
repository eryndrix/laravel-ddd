<?php declare(strict_types=1);

namespace App\Identity\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use App\Identity\Domain\Email\Email;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\Id\UserId;
use App\Identity\Domain\Access\TokenHash;
use App\Identity\Domain\User;

final class UserRepository implements UserRepositoryInterface
{
    /**
     * @phpstan-param EntityManagerInterface $entityManager
     */
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @phpstan-param UserId $id
     * @phpstan-return User|null
     */
    public function findById(UserId $id): ?User
    {
        $qb = $this->entityManager->createQueryBuilder();
        
        /** @phpstan-var User|null $user */
        $user = $qb->select('u')
            ->from(from: User::class, alias: 'u')
            ->where('u.id = :id')
            ->setParameter(key: 'id', value: $id)
            ->getQuery()
            ->getOneOrNullResult();

        return $user;
    }

    /**
     * @phpstan-param Email $email
     * @phpstan-return User|null
     */
    public function findByEmail(Email $email): ?User
    {
        $qb = $this->entityManager->createQueryBuilder();
        
        /** @phpstan-var User|null $user */
        $user = $qb->select('u')
            ->from(from: User::class, alias: 'u')
            ->where('u.email.email = :email')
            ->setParameter(key: 'email', value: (string) $email)
            ->getQuery()
            ->getOneOrNullResult();

        return $user;
    }

    /**
     * @phpstan-param UserId $id
     * @phpstan-param TokenHash $token
     * 
     * @phpstan-return User|null
     */
    public function findByToken(UserId $id, TokenHash $token): ?User
    {
        $qb = $this->entityManager->createQueryBuilder();
        
        /** @phpstan-var User|null $user */
        $user = $qb->select('u')
            ->from(from: User::class, alias: 'u')
            ->where('u.id = :id')
            ->andWhere('u.rememberToken = :token')
            ->setParameter(key: 'id', value: $id)
            ->setParameter(key: 'token', value: (string) $token)
            ->getQuery()
            ->getOneOrNullResult();

        return $user;
    }

    /**
     * @phpstan-param User $user
     * @phpstan-return void
     */
    public function save(User $user): void
    {
        $this->entityManager->persist(object: $user);
    }

    /**
     * @phpstan-param User $user
     * @phpstan-return void
     */
    public function remove(User $user): void
    {
        $this->entityManager->remove(object: $user);
    }
}
