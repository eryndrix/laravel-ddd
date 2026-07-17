<?php declare(strict_types=1);

namespace App\Shared\Domain\Contract;

interface LoggerInterface
{
    /**
     * @phpstan-param string $message
     * @phpstan-param \Throwable $exception
     * 
     * @phpstan-return void
     */
    public function critical(string $message, \Throwable $exception): void;

    /**
     * @phpstan-param string $message
     * @phpstan-param \Throwable $exception
     * 
     * @phpstan-return void
     */
    public function error(string $message, \Throwable $exception): void;

    /**
     * @phpstan-param string $message
     * @phpstan-param \Throwable $exception
     * 
     * @phpstan-return void
     */
    public function warning(string $message, \Throwable $exception): void;

    /**
     * @phpstan-param string $message
     * @phpstan-param \Throwable $exception
     * 
     * @phpstan-return void
     */
    public function notice(string $message, \Throwable $exception): void;
}
