<?php declare(strict_types=1);

namespace App\Identity\Infrastructure\Jwt;

use Illuminate\Support\ServiceProvider;
use App\Identity\Domain\Access\Jwt\JwtTokenManagerInterface;
use App\Identity\Domain\Access\Jwt\JwtTokenIssuerInterface;
use Illuminate\Support\Facades\URL;

final class JwtSecurityBootstrapper extends ServiceProvider
{
    /**
     * @phpstan-return void
     */
    public function register(): void
    {
        $this->app->singleton(
            abstract: JwtTokenManagerInterface::class,
            concrete: JwtTokenManager::class
        );
        
        $this->app->singleton(
            abstract: JwtTokenIssuerInterface::class,
            concrete: JwtTokenIssuer::class
        );
    }

    /**
     * @phpstan-return void
     */
    public function boot(): void
    {
        $secret = config(key: 'jwt.secret');
        
        if (!is_string(value: $secret)
            || strlen(string: $secret) < 32
        ) {
            throw new \RuntimeException(
                message: 'Secret must be at least 32 characters.'
            );
        }

        if (true === $this->app->environment('production')) {
            URL::forceScheme(scheme: 'https');
        }
    }
}
