<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token\Refresh\Exception;

final class TokenExpiredException extends \LogicException
{
    /**
     * @phpstan-param string $message
     * @phpstan-param int $code
     * @phpstan-param \Throwable|null $previous
     */
    public function __construct(
        string $message = 'Refresh token expired.',
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
