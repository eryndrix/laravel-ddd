<?php declare(strict_types=1);

namespace App\Identity\Infrastructure;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use App\Shared\Domain\Id\UserId;

final class IdentityRouteProvider extends ServiceProvider
{
    /**
     * @phpstan-return void
     */
    public function boot(): void
    {
        Route::bind(key: 'userId',
            binder: function (string $id): UserId {
                return UserId::of(value: $id);
            }
        );
    }
}
