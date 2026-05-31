<?php declare(strict_types=1);

namespace App\Shared\Domain\Id;

/**
 * @phpstan-extends UniqueId<RoleId>
 */
final class RoleId extends UniqueId
{
    /**
     * @phpstan-use IdFactory<RoleId>
     */
    use IdFactory;
}
