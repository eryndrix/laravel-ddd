<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login\Exception;

use App\Shared\Application\Exception\ExceptionResolver;
use App\Shared\Application\Exception\Handler\DomainExceptionHandler;
use App\Shared\Application\Exception\Handler\UserNotFoundExceptionHandler;
use App\Shared\Application\Exception\Handler\UnexpectedExceptionHandler;
use App\Shared\Domain\Contract\ExceptionHandlerInterface;

final class LoginExceptionResolver extends ExceptionResolver
{
    /**
     * @phpstan-var array<class-string<ExceptionHandlerInterface>>
     */
    protected array $exceptionHandlers = [
        DomainExceptionHandler::class,
        InvalidCredentialsExceptionHandler::class,
        JwtTokenIssuanceExceptionHandler::class,
        UserNotFoundExceptionHandler::class,
        UnexpectedExceptionHandler::class
    ];
}
