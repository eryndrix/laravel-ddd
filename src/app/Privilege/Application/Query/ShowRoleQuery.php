<?php declare(strict_types=1);

namespace App\Privilege\Application\Query;

use App\Shared\Domain\Id\RoleId;

/**
 * @phpstan-template TRoleId of RoleId
 */
final class ShowRoleQuery
{
    /**
     * @phpstan-param RoleId $roleId
     */
    public function __construct(
        public private(set) RoleId $roleId
    ) {}
}
