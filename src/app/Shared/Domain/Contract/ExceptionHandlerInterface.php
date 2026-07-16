<?php declare(strict_types=1);

namespace App\Shared\Domain\Contract;

interface ExceptionHandlerInterface
{
    /**
     * @phpstan-param \Throwable $e
     * @phpstan-return bool
     */
    public function supports(\Throwable $e): bool;

    /**
     * @phpstan-param \Throwable $e
     * @phpstan-return \App\Shared\Application\Result\Result<never, \Throwable>
     */
    public function map(\Throwable $e);
}
