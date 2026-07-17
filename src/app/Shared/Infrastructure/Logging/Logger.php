<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Logging;

use App\Shared\Domain\Contract\LoggerInterface;
use Illuminate\Support\Facades\Log;

final class Logger implements LoggerInterface
{
    /**
     * @phpstan-param string $message
     * @phpstan-param \Throwable $exception
     * 
     * @phpstan-return void
     */
    public function critical(
        string $message, \Throwable $exception): void
    {
        Log::critical(
            message: $message,
            context: [
                'exception' => $exception::class,
                'code' => (int) $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ]
        );
    }

    /**
     * @phpstan-param string $message
     * @phpstan-param \Throwable $exception
     * 
     * @phpstan-return void
     */
    public function error(
        string $message, \Throwable $exception): void
    {
        Log::error(
            message: $message,
            context: [
                'exception' => $exception::class,
                'code' => (int) $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]
        );
    }
    
    /**
     * @phpstan-param string $message
     * @phpstan-param \Throwable $exception
     * 
     * @phpstan-return void
     */
    public function warning(
        string $message, \Throwable $exception): void
    {
        Log::warning(
            message: $message,
            context: [
                'exception' => $exception::class,
                'code' => (int) $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]
        );
    }
    
    /**
     * @phpstan-param string $message
     * @phpstan-param \Throwable $exception
     * 
     * @phpstan-return void
     */
    public function notice(
        string $message, \Throwable $exception): void
    {
        Log::notice(
            message: $message,
            context: [
                'exception' => $exception::class,
                'code' => (int) $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]
        );
    }
}
