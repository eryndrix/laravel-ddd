<?php declare(strict_types=1);

namespace App\Privilege\Application\Handler;

use App\Shared\Application\Handler;
use App\Privilege\Application\Query\ListRoleQuery;
use App\Privilege\Domain\Repository\RoleRepositoryInterface;
use App\Privilege\Application\RoleSuccess;
use App\Privilege\Application\RoleError;
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
     * @phpstan-return Result<RoleSuccess<
     *     Paginator<\App\Privilege\Domain\Role>
     * >, RoleError>
     */
    public function handle(ListRoleQuery $query): Result
    {
        $perPage = $query->perPage;

        if ($perPage < 10 || $perPage > 100) {
            return Result::failure(
                error: RoleError::PerPageOutOfRange
            );
        }

        $roles = new Paginator(
            items: $this->repository->all(),
            perPage: $perPage
        );

        $result = new RoleSuccess(result: $roles);
        return Result::success(value: $result);
    }
}
