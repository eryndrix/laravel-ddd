<?php declare(strict_types=1);

namespace App\Privilege\Presentation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Privilege\Domain\Permission;

/**
 * @mixin \App\Privilege\Domain\Role
 */
final class RoleResource extends JsonResource
{
    /**
     * @phpstan-param Request $request
     * @phpstan-return array{
     *   id: string,
     *   name: string,
     *   slug: string,
     *   permissions: array<array{
     *     id: string,
     *     name: string,
     *     slug: string,
     *     created_at: string|null,
     *     updated_at: string|null
     *   }>,
     *   datetime: array{
     *     created_at: string|null,
     *     updated_at: string|null
     *   }
     * }
     */
    public function toArray(Request $request): array
    {
        /** @phpstan-var \App\Privilege\Domain\Role $role */
        $role = $this->resource;

        return [
            'id' => $role->id->value(),
            'name' => $role->name,
            'slug' => $role->slug->value(),
            'permissions' => array_map(
                callback: function (Permission $permission): array {
                    return [
                        'id' => $permission->id->value(),
                        'name' => $permission->name,
                        'slug' => $permission->slug->value(),
                        'created_at' => $permission->createdAt?->format(
                            format: 'Y-m-d H:i:s'
                        ),
                        'updated_at' => $permission->updatedAt?->format(
                            format: 'Y-m-d H:i:s'
                        ),
                    ];
                },
                array: $role->permissions->toArray()
            ),
            'datetime' => [
                'created_at' => $role->createdAt?->format(
                    format: 'Y-m-d H:i:s'
                ),
                'updated_at' => $role->updatedAt?->format(
                    format: 'Y-m-d H:i:s'
                ),
            ]
        ];
    }
}
