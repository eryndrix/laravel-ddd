<?php declare(strict_types=1);

namespace App\Identity\Infrastructure\Auth;

use Illuminate\Support\ServiceProvider;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use Illuminate\Foundation\Application as App;
use App\Identity\Domain\Access\Auth\AuthenticatorInterface;
use App\Identity\Domain\Access\Auth\UserProviderInterface;
use Illuminate\Support\Facades\Auth;

final class AuthServiceProvider extends ServiceProvider
{
    /**
     * @phpstan-return void
     */
    public function register(): void
    {
        $this->app->bind(
            abstract: UserProviderInterface::class,
            concrete: UserProvider::class
        );

        $this->app->bind(
            abstract: AuthenticatorInterface::class,
            concrete: Authenticator::class
        );
    }
    
    /**
     * @phpstan-return void
     */
    public function boot(): void
    {
        Auth::provider(name: 'doctrine',
            callback: fn (App $app, array $config): UserProvider
                => new UserProvider(
                    repository: $app->make(
                        abstract: UserRepositoryInterface::class
                    )
                )
        );
    }
}
