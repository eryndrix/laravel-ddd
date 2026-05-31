<?php declare(strict_types=1);

namespace App\Privilege\Infrastructure\Repository;

use App\Privilege\Domain\Role;
use Doctrine\ORM\EntityManagerInterface;
use App\Privilege\Domain\Repository\RoleRepositoryInterface;
use App\Shared\Domain\Id\RoleId;
use App\Shared\Domain\Slug\RoleSlug;

/**
 * @phpstan-implements RoleRepositoryInterface<Role>
 */
final class RoleRepository implements RoleRepositoryInterface
{
    /**
     * @phpstan-param EntityManagerInterface $entityManager
     */
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @phpstan-return list<Role>
     */
    public function all(): array
    {
        $qb = $this->entityManager->createQueryBuilder();

        /** @phpstan-var list<Role> $result */
        $result = $qb->select('r')
            ->from(from: Role::class, alias: 'r')
            ->orderBy(sort: 'r.createdAt', order: 'ASC')
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * @phpstan-param RoleId $id
     * @phpstan-return Role|null
     */
    public function findById(RoleId $id): ?Role
    {
        $qb = $this->entityManager->createQueryBuilder();

        /** @phpstan-var Role|null $result */
        $result = $qb->select('r')
            ->from(from: Role::class, alias: 'r')
            ->where('r.id = :id')
            ->setParameter(key: 'id', value: $id)
            ->getQuery()
            ->getOneOrNullResult();

        return $result;
    }

    /**
     * @phpstan-param RoleSlug $slug
     * @phpstan-return Role|null
     */
    public function findBySlug(RoleSlug $slug): ?Role
    {
        $qb = $this->entityManager->createQueryBuilder();

        /** @phpstan-var Role|null $result */
        $result = $qb->select('r')
            ->from(from: Role::class, alias: 'r')
            ->where('r.slug = :slug')
            ->setParameter(key: 'slug', value: $slug)
            ->getQuery()
            ->getOneOrNullResult();

        return $result;
    }
}
