<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token\Refresh\Exception;

final class TokenReuseDetectedException extends \LogicException
{
    /**
     * @phpstan-param string $message
     * @phpstan-param int $code
     * @phpstan-param \Throwable|null $previous
     */
    public function __construct(
        string $message = 'Refresh token reuse detected.',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            message: $message,
            code: $code,
            previous: $previous
        );
    }
}
