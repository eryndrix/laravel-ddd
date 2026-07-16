<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Logging;

use Illuminate\Support\ServiceProvider;
use App\Shared\Domain\Contract\LoggerInterface;

final class LoggerServiceProvider extends ServiceProvider
{
    /**
     * @phpstan-return void
     */
    public function register(): void
    {
        $this->app->bind(
            abstract: LoggerInterface::class,
            concrete: Logger::class
        );
    }
}
