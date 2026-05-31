<?php declare(strict_types=1);

namespace App\Privilege\Infrastructure\Mapping;

use App\Privilege\Domain\Role;
use App\Privilege\Domain\Permission;
use App\Shared\Domain\Slug\RoleSlug;
use App\Shared\Domain\Id\RoleId;

/**
 * @phpstan-template TPrimitives of array{
 *     id: string,
 *     name: string,
 *     slug: string,
 *     permissions: list<array{
 *         id: string,
 *         name: string,
 *         slug: string,
 *         guard: string,
 *         created_at: string|null,
 *         updated_at: string|null
 *     }>,
 *     created_at: string|null,
 *     updated_at: string|null
 * }
 */
final class RoleMapper
{
    /**
     * @phpstan-param Role $role
     * @phpstan-return array{
     *     id: string,
     *     name: string,
     *     slug: string,
     *     permissions: list<array{
     *         id: string,
     *         name: string,
     *         slug: string,
     *         guard: string,
     *         created_at: string|null,
     *         updated_at: string|null
     *     }>,
     *     created_at: string|null,
     *     updated_at: string|null
     * }
     */
    public static function toPrimitives(Role $role): array
    {
        /** @phpstan-var list<array{
         *     id: string,
         *     name: string,
         *     slug: string,
         *     guard: string,
         *     created_at: string|null,
         *     updated_at: string|null
         * }> $permissions
         */
        $permissions = array_map(
            callback: function (Permission $permission): array {
                return PermissionMapper::toPrimitives(
                    permission: $permission
                );
            },
            array: $role->permissions->toArray()
        );

        return [
            'id' => $role->id->value(),
            'name' => $role->name,
            'slug' => $role->slug->value(),
            'permissions' => $permissions,
            'created_at' => $role->createdAt !== null
                ? $role->createdAt->format(format: 'Y-m-d H:i:s')
                : null,
            'updated_at' => $role->updatedAt !== null
                ? $role->updatedAt->format(format: 'Y-m-d H:i:s')
                : null,
        ];
    }

    /**
     * @phpstan-param array{
     *     id: string,
     *     name: string,
     *     slug: string,
     *     permissions?: list<array>
     * } $data
     * @phpstan-return Role
     */
    public static function fromPrimitives(array $data): Role
    {
        $role = new Role(
            id: RoleId::of(value: $data['id']),
            name: $data['name'],
            slug: RoleSlug::of(value: $data['slug'])
        );

        foreach ($data['permissions'] ?? [] as $permission) {
            /**
             * @phpstan-var array{
             *     id: string,
             *     name: string,
             *     slug: string,
             *     guard: string
             * } $permission
             */
            $role->assignPermission(
                permission: PermissionMapper::fromPrimitives(
                    data: $permission
                )
            );
        }

        return $role;
    }
}
