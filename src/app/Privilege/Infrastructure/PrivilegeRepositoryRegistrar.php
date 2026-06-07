<?php declare(strict_types=1);

namespace App\Privilege\Infrastructure;

use Illuminate\Support\ServiceProvider;
use App\Privilege\Infrastructure\Repository\CachedRoleRepository;

final class PrivilegeRepositoryRegistrar extends ServiceProvider
{
    /**
     * @phpstan-return void
     */
    public function register(): void
    {
        $this->app->bind(
            abstract: \App\Privilege\Domain\Repository\RoleRepositoryInterface::class,
            concrete: CachedRoleRepository::class
        );

        $this->app->bind(
            abstract: \App\Shared\Domain\Repository\RoleRepositoryInterface::class,
            concrete: CachedRoleRepository::class
        );
    }
}
