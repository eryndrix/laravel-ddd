<?php declare(strict_types=1);

namespace App\Shared\Application\Exception;

use App\Shared\Application\Result\Result;
use App\Shared\Domain\Contract\ExceptionHandlerInterface;
use Illuminate\Foundation\Application;

abstract class ExceptionResolver
{
    /**
     * @phpstan-var array<class-string<ExceptionHandlerInterface>>
     */
    protected array $exceptionHandlers = [];

    /**
     * @phpstan-param \Throwable $e
     * @phpstan-return Result<never, \Throwable>
     */
    public function map(\Throwable $e): Result
    {
        foreach ($this->exceptionHandlers as $className) {
            $exceptionHandler = app()->make(
                abstract: $className
            );

            if ($exceptionHandler->supports(e: $e)) {
                return $exceptionHandler->map(e: $e);
            }
        }

        return Result::failure(error: $e);
    }
}
