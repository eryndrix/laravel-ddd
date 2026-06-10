<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Register\Handler;

use App\Shared\Application\Handler\Handler;
use App\Shared\Domain\Repository\RoleRepositoryInterface;
use App\Shared\Domain\Slug\RoleSlug;
use App\Identity\Application\Auth\Register\RegisterCommand;
use App\Privilege\Domain\Role;
use App\Shared\Application\Result\Result;

final class AttachDefaultRoleHandler
{
    /**
     * @phpstan-param \App\Privilege\Domain\Repository\RoleRepositoryInterface<Role> $repository
     */
    public function __construct(
        private RoleRepositoryInterface $repository
    ) {}

    /**
     * @phpstan-param RegisterCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws \RuntimeException
     */
    public function handle(
        RegisterCommand $command, \Closure $next): mixed
    {
        $role = $this->repository->findBySlug(
            slug: RoleSlug::of(value: 'user')
        );

        if (!$role instanceof Role) {
            throw new \RuntimeException(
                message: 'Role with slug "user" not found.'
            );
        }
        
        $command->roleId = $role->id;

        return $next($command);
    }
}
