<?php declare(strict_types=1);

namespace App\Shared\Application\Exception\Handler;

use App\Shared\Domain\Contract\ExceptionHandlerInterface;

abstract class ExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * @phpstan-var string
     */
    protected const string MESSAGE = '';

    /**
     * @phpstan-param string $default
     * @phpstan-return string
     */
    protected function message(string $default): string
    {
        return static::MESSAGE !== '' ? static::MESSAGE : $default;
    }
}
