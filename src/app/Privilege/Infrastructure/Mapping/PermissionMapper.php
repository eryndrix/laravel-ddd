<?php declare(strict_types=1);

namespace App\Privilege\Infrastructure\Mapping;

use App\Privilege\Domain\Permission;
use App\Shared\Domain\Slug\PermissionSlug;
use App\Privilege\Domain\Guard;
use App\Shared\Domain\Id\PermissionId;

final class PermissionMapper
{
    /**
     * @phpstan-param Permission $permission
     * 
     * @phpstan-return array{
     *     id: string,
     *     name: string,
     *     slug: string,
     *     guard: string,
     *     created_at: string|null,
     *     updated_at: string|null
     * }
     */
    public static function toArray(Permission $permission): array
    {
        return [
            'id' => $permission->id->value(),
            'name' => $permission->name,
            'slug' => $permission->slug->value(),
            'guard' => $permission->guard->value,
            'created_at' => $permission->createdAt !== null
                ? $permission->createdAt->format(format: 'Y-m-d H:i:s')
                : null,
            'updated_at' => $permission->updatedAt !== null
                ? $permission->updatedAt->format(format: 'Y-m-d H:i:s')
                : null,
        ];
    }

    /**
     * @phpstan-param array{
     *     id: string,
     *     name: string,
     *     slug: string,
     *     guard: string
     * } $data
     * 
     * @phpstan-return Permission
     */
    public static function fromArray(array $data): Permission
    {
        return new Permission(
            id: PermissionId::of(value: $data['id']),
            name: $data['name'],
            slug: PermissionSlug::of(value: $data['slug']),
            guard: Guard::from(value: $data['guard']),
        );
    }
}
