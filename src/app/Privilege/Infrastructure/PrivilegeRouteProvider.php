<?php declare(strict_types=1);

namespace App\Privilege\Infrastructure;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use App\Shared\Domain\Id\RoleId;

final class PrivilegeRouteProvider extends ServiceProvider
{
    /**
     * @phpstan-return void
     */
    public function boot(): void
    {
        Route::bind(key: 'roleId',
            binder: function (string $id): RoleId {
                return RoleId::of(value: $id);
            }
        );
    }
}
