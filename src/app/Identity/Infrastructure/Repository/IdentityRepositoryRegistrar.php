<?php declare(strict_types=1);

namespace App\Identity\Infrastructure\Repository;

use Illuminate\Support\ServiceProvider;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Domain\Repository\TokenRepositoryInterface;

final class IdentityRepositoryRegistrar extends ServiceProvider
{
    /**
     * @phpstan-return void
     */
    public function register(): void
    {
        $this->app->bind(
            abstract: UserRepositoryInterface::class,
            concrete: UserRepository::class
        );

        $this->app->bind(
            abstract: TokenRepositoryInterface::class,
            concrete: TokenRepository::class
        );
    }
}
