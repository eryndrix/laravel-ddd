<?php declare(strict_types=1);

namespace App\Privilege\Application\Query;

use App\Shared\Application\Query\Query;

/**
 * @phpstan-template TPerPage of int
 */
final class ListRoleQuery extends Query
{
    /**
     * @phpstan-param int $perPage
     */
    public function __construct(
        public private(set) int $perPage = 15
    ) {}
}
