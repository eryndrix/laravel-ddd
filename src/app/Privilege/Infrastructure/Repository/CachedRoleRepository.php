<?php declare(strict_types=1);

namespace App\Privilege\Infrastructure\Repository;

use Illuminate\Support\Facades\Cache;
use App\Privilege\Domain\Repository\RoleRepositoryInterface;
use App\Privilege\Infrastructure\Mapping\RoleMapper;
use App\Shared\Domain\Id\RoleId;
use App\Shared\Domain\Slug\RoleSlug;
use App\Privilege\Domain\Role;

/**
 * @phpstan-implements RoleRepositoryInterface<Role>
 */
final class CachedRoleRepository implements RoleRepositoryInterface
{
    /**
     * @phpstan-param RoleRepository $repository
     */
    public function __construct(
        private readonly RoleRepository $repository
    ) {}

    /**
     * @phpstan-return list<Role>
     */
    public function all(): array
    {
        /**
         * @phpstan-var array<int,
         *     array{
         *         id: string,
         *         name: string,
         *         slug: string,
         *         permissions?: list<array>,
         *         created_at: string|null,
         *         updated_at: string|null
         *     }
         * > $data
         */
        $data = Cache::rememberForever(
            key: 'roles_all',
            callback: fn(): array => array_map(
                callback: function (Role $role): array {
                    return RoleMapper::toArray(role: $role);
                },
                array: $this->repository->all()
            )
        );

        /** @phpstan-var list<Role> $roles */
        $roles = array_map(
            callback: function (array $role): Role {
                return RoleMapper::fromArray(data: $role);
            },
            array: $data
        );

        return $roles;
    }

    /**
     * @phpstan-param RoleId $id
     * @phpstan-return Role|null
     */
    public function findById(RoleId $id): ?Role
    {
        /**
         * @phpstan-var array{
         *     id: string,
         *     name: string,
         *     slug: string,
         *     permissions?: list<array>,
         *     created_at: string|null,
         *     updated_at: string|null
         * }|null $data
         */
        $data = Cache::rememberForever(
            key: 'role_' . (string) $id,
            callback: function() use($id): ?array {
                $role = $this->repository->findById(
                    id: $id
                );

                return $role !== null
                    ? RoleMapper::toArray(role: $role)
                    : null;
            }
        );

        return $data !== null
            ? RoleMapper::fromArray(data: $data)
            : null;
    }

    /**
     * @phpstan-param RoleSlug $slug
     * @phpstan-return Role|null
     */
    public function findBySlug(RoleSlug $slug): ?Role
    {
        /**
         * @phpstan-var array{
         *     id: string,
         *     name: string,
         *     slug: string,
         *     permissions?: list<array>,
         *     created_at: string|null,
         *     updated_at: string|null
         * }|null $data
         */
        $data = Cache::rememberForever(
            key: 'role_' . (string) $slug,
            callback: function() use($slug): ?array {
                $role = $this->repository->findBySlug(
                    slug: $slug
                );

                return $role !== null
                    ? RoleMapper::toArray(role: $role)
                    : null;
            }
        );

        return $data !== null
            ? RoleMapper::fromArray(data: $data)
            : null;
    }
}
