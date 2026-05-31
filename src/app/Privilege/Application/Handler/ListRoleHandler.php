<?php declare(strict_types=1);

namespace App\Privilege\Application\Handler;

use App\Shared\Application\Handler;
use App\Privilege\Application\Query\ListRoleQuery;
use App\Privilege\Domain\Repository\RoleRepositoryInterface;
use App\Shared\Application\Result\Result;
use Eryndrix\Paginator\Paginator;

final class ListRoleHandler extends Handler
{
    /**
     * @phpstan-param RoleRepositoryInterface<
     *     \App\Privilege\Domain\Role
     * > $repository
     */
    public function __construct(
        private RoleRepositoryInterface $repository
    ) {}
    
    /**
     * @phpstan-param ListRoleQuery<int> $query
     * 
     * @phpstan-return Result<
     *     \Eryndrix\Paginator\Paginator<\App\Privilege\Domain\Role>,
     *     string
     * >
     */
    public function handle(ListRoleQuery $query): Result
    {
        $perPage = $query->perPage;

        if ($perPage < 10 || $perPage > 100) {
            return Result::failure(
                error: 'perPage must be between 10-100.'
            );
        }

        $roles = new Paginator(
            items: $this->repository->all(),
            perPage: $query->perPage
        );

        return Result::success(value: $roles);
}
}
