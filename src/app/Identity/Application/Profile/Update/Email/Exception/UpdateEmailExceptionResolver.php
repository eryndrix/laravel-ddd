<?php declare(strict_types=1);

namespace App\Identity\Application\Profile\Update\Email\Exception;

use App\Shared\Application\Exception\ExceptionResolver;
use App\Shared\Application\Exception\Handler\DomainExceptionHandler;
use App\Shared\Application\Exception\Handler\UserNotFoundExceptionHandler;
use App\Shared\Application\Exception\Handler\UnexpectedExceptionHandler;
use App\Shared\Domain\Contract\ExceptionHandlerInterface;

final class UpdateEmailExceptionResolver extends ExceptionResolver
{
    /**
     * @phpstan-var array<class-string<ExceptionHandlerInterface>>
     */
    protected array $exceptionHandlers = [
        DomainExceptionHandler::class,
        EmailUnchangedExceptionHandler::class,
        UserNotFoundExceptionHandler::class,
        UnexpectedExceptionHandler::class
    ];
}
