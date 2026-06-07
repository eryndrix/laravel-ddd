<?php declare(strict_types=1);

namespace App\Privilege\Domain\Repository;

use App\Shared\Domain\{Slug\RoleSlug, Id\RoleId};
use App\Shared\Domain\Repository\RoleRepositoryInterface as RepositoryInterface;
use App\Privilege\Domain\Role;

/**
 * @phpstan-template TRole of Role
 */
interface RoleRepositoryInterface extends RepositoryInterface
{
    /**
     * @phpstan-return list<TRole>
     */
    public function all(): array;

    /**
     * @phpstan-param RoleId $id
     * @phpstan-return TRole|null
     */
    public function findById(RoleId $id): ?Role;

    /**
     * @phpstan-param RoleSlug $slug
     * @phpstan-return TRole|null
     */
    public function findBySlug(RoleSlug $slug): ?Role;
}
