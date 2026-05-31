<?php declare(strict_types=1);

namespace App\Shared\Infrastructure;

use App\Shared\Domain\Storage\UnitOfWorkInterface;
use Illuminate\Support\ServiceProvider;

final class DoctrinePersistenceBinder extends ServiceProvider
{
    /**
     * @phpstan-return void
     */
    public function register(): void
    {
        $this->app->singleton(
            abstract: UnitOfWorkInterface::class,
            concrete: UnitOfWork::class
        );
    }
}
