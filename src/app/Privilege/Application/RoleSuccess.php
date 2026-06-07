<?php declare(strict_types=1);

namespace App\Privilege\Application;

/**
 * @phpstan-template-covariant TResult
 */
final class RoleSuccess
{
    /**
     * @phpstan-param TResult $result
     */
    public function __construct(
        public private(set) mixed $result
    ) {}
}
