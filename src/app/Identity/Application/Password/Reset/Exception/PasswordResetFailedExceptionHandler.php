<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Reset\Exception;

use App\Shared\Domain\Contract\LoggerInterface;
use App\Shared\Application\Exception\Handler\ExceptionHandler;
use App\Shared\Application\Result\Result;

final class PasswordResetFailedExceptionHandler extends ExceptionHandler
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
        return $e instanceof PasswordResetFailedException;
    }

    /**
     * @phpstan-param \Throwable $e
     * @phpstan-return Result<never, \Throwable>
     */
    public function map(\Throwable $e): Result
    {
        $this->logger->error(
            message: 'Password reset failed.',
            exception: $e
        );

        return Result::failure(error: $e);
    }
}
