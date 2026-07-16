<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\User;

use App\Identity\Domain\User;
use App\Shared\Application\Query\Query;

/**
 * @phpstan-template TEntity of User
 */
final class UserQuery extends Query
{
    /**
     * @phpstan-param TEntity $user
     */
    public function __construct(
        public private(set) User $user
    ) {}
}
