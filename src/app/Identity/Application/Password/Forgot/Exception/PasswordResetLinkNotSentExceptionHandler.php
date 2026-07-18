<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Forgot\Exception;

use App\Shared\Domain\Contract\LoggerInterface;
use App\Shared\Application\Exception\Handler\ExceptionHandler;
use App\Shared\Application\Result\Result;

final class PasswordResetLinkNotSentExceptionHandler extends ExceptionHandler
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
        return $e instanceof PasswordResetLinkNotSentException;
    }

    /**
     * @phpstan-param \Throwable $e
     * @phpstan-return Result<never, \Throwable>
     */
    public function map(\Throwable $e): Result
    {
        $this->logger->error(
            message: 'Password reset link not sent.',
            exception: $e
        );

        return Result::failure(error: $e);
    }
}
