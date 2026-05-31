<?php declare(strict_types=1);

namespace App\Privilege\Infrastructure;

use Illuminate\Support\ServiceProvider;
use App\Privilege\Domain\Repository\RoleRepositoryInterface;
use App\Privilege\Infrastructure\Repository\CachedRoleRepository;
use App\Privilege\Infrastructure\Repository\RoleRepository;
use Illuminate\Foundation\Application;

final class PrivilegeRepositoryRegistrar extends ServiceProvider
{
    /**
     * @phpstan-return void
     */
    public function register(): void
    {
        $this->app->bind(
            abstract: RoleRepositoryInterface::class,
            concrete: fn (Application $app) => new CachedRoleRepository(
                repository: $app->make(
                    abstract: RoleRepository::class
                )
            )
        );
    }
}
