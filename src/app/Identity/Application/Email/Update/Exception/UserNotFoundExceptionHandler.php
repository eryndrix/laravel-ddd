<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Update\Exception;

use App\Shared\Domain\Contract\LoggerInterface;
use App\Shared\Application\Exception\Handler\ExceptionHandler;
use App\Shared\Application\Result\Result;

final class UserNotFoundExceptionHandler extends ExceptionHandler
{
    /**
     * @phpstan-param LoggerInterface $logger
     */
    public function __construct(
        public readonly LoggerInterface $logger
    ) {}

    /**
     * @phpstan-param \Throwable $e
     * @phpstan-return bool
     */
    public function supports(\Throwable $e): bool
    {
        return $e instanceof UserNotFoundException;
    }

    /**
     * @phpstan-param \Throwable $e
     * @phpstan-return Result<never, \Throwable>
     */
    public function map(\Throwable $e): Result
    {
        $this->logger->error(
            message: 'User must not be null.',
            exception: $e
        );

        return Result::failure(error: $e);
    }
}