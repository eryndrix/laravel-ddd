<?php declare(strict_types=1);

namespace App\Privilege\Application\Handler;

use App\Shared\Application\Handler;
use App\Privilege\Application\Query\ShowRoleQuery;
use App\Privilege\Domain\Repository\RoleRepositoryInterface;
use App\Privilege\Application\RoleSuccess;
use App\Privilege\Application\RoleError;
use App\Shared\Application\Result\Result;

final class ShowRoleHandler extends Handler
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
     * @phpstan-param ShowRoleQuery<
     *     \App\Shared\Domain\Id\RoleId
     * > $query
     * 
     * @phpstan-return Result<
     *     RoleSuccess<\App\Privilege\Domain\Role>,
     *     RoleError
     * >
     */
    public function handle(ShowRoleQuery $query): Result
    {
        $role = $this->repository->findById(
            id: $query->roleId
        );

        if (is_null(value: $role)) {
            return Result::failure(
                error: RoleError::NotFound
            );
        }

        $result = new RoleSuccess(result: $role);
        return Result::success(value: $result);
    }
}
