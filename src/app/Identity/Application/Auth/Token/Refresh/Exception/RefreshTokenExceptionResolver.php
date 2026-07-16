<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token\Refresh\Exception;

use App\Shared\Application\Exception\ExceptionResolver;
use App\Shared\Application\Exception\Handler\DomainExceptionHandler;
use App\Shared\Application\Exception\Handler\UserNotFoundExceptionHandler;
use App\Shared\Application\Exception\Handler\UnexpectedExceptionHandler;
use App\Shared\Domain\Contract\ExceptionHandlerInterface;

final class RefreshTokenExceptionResolver extends ExceptionResolver
{
    /**
     * @phpstan-var array<class-string<ExceptionHandlerInterface>>
     */
    protected array $exceptionHandlers = [
        DomainExceptionHandler::class,
        TokenNotFoundExceptionHandler::class,
        TokenExpiredExceptionHandler::class,
        MissingAbilityExceptionHandler::class,
        TokenReuseDetectedExceptionHandler::class,
        UserNotFoundExceptionHandler::class,
        TokenIssuanceExceptionHandler::class,
        UnexpectedExceptionHandler::class
    ];
}
