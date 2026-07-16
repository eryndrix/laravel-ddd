<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\User;

use App\Shared\Application\Exception\ExceptionResolver;
use App\Shared\Application\Exception\Handler\DomainExceptionHandler;
use App\Shared\Application\Exception\Handler\UnexpectedExceptionHandler;
use App\Shared\Domain\Contract\ExceptionHandlerInterface;

final class UserExceptionResolver extends ExceptionResolver
{
    /**
     * @phpstan-var array<class-string<ExceptionHandlerInterface>>
     */
    protected array $exceptionHandlers = [
        DomainExceptionHandler::class,
        UnexpectedExceptionHandler::class
    ];
}
