<?php declare(strict_types=1);

namespace App\Shared\Domain\Id;

/**
 * @phpstan-extends UniqueId<PermissionId>
 */
final class PermissionId extends UniqueId
{
    /**
     * @phpstan-use IdFactory<PermissionId>
     */
    use IdFactory;
}
