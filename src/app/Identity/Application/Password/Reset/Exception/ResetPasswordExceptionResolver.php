<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Reset\Exception;

use App\Shared\Application\Exception\ExceptionResolver;
use App\Shared\Application\Exception\Handler\DomainExceptionHandler;
use App\Shared\Application\Exception\Handler\UnexpectedExceptionHandler;
use App\Shared\Domain\Contract\ExceptionHandlerInterface;

final class ResetPasswordExceptionResolver extends ExceptionResolver
{
    /**
     * @phpstan-var array<class-string<ExceptionHandlerInterface>>
     */
    protected array $exceptionHandlers = [
        InvalidResetEmailExceptionHandler::class,
        InvalidResetTokenExceptionHandler::class,
        PasswordResetFailedExceptionHandler::class,
        DomainExceptionHandler::class,
        UnexpectedExceptionHandler::class,
    ];
}
