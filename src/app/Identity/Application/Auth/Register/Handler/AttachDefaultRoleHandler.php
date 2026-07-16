<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Register\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Auth\Register\RegisterCommand;
use App\Shared\Domain\Repository\RoleRepositoryInterface;
use App\Identity\Application\Auth\Register\Exception\DefaultRoleNotFoundException;
use App\Shared\Domain\Slug\RoleSlug;
use App\Shared\Application\Result\Result;

final class AttachDefaultRoleHandler extends Handler
{
    /**
     * @phpstan-param \App\Privilege\Domain\Repository\RoleRepositoryInterface $repository
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
     * @throws \LogicException
     */
    public function handle(
        RegisterCommand $command, \Closure $next): mixed
    {
        $role = $this->repository->findBySlug(
            slug: RoleSlug::of(value: 'user')
        );

        if (is_null(value: $role)) {
            throw new DefaultRoleNotFoundException();
        }
        
        $command->roleId = $role->id;

        return $next($command);
    }
}
