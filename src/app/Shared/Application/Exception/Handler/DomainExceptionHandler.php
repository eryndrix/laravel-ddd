<?php declare(strict_types=1);

namespace App\Shared\Application\Exception\Handler;

use App\Shared\Domain\Contract\LoggerInterface;
use App\Shared\Application\Result\Result;

class DomainExceptionHandler extends ExceptionHandler
{
    /**
     * @phpstan-param LoggerInterface $logger
     */
    public function __construct(
        private readonly LoggerInterface $logger
    ) {}

    /**
     * @phpstan-param \Throwable $e
     * @phpstan-return bool
     */
    public function supports(\Throwable $e): bool
    {
        return $e instanceof \DomainException;
    }

    /**
     * @phpstan-param \Throwable $e
     * @phpstan-return Result<never, \Throwable>
     */
    public function map(\Throwable $e): Result
    {
        $this->logger->warning(
            message: $this->message(
                default: 'Domain error occurred.'
            ),
            exception: $e
        );

        return Result::failure(error: $e);
    }
}
