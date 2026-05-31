<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Id;

use App\Shared\Domain\Id\PermissionId;

/**
 * @phpstan-extends UniqueIdType<PermissionId>
 */
final class PermissionIdType extends UniqueIdType
{
    /**
     * @phpstan-var class-string<PermissionId>
     */
    public const string NAME = PermissionId::class;

    /**
     * @phpstan-return class-string<PermissionId>
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @phpstan-return class-string<PermissionId>
     */
    public function getValueObjectClass(): string
    {
        return PermissionId::class;
    }
}
