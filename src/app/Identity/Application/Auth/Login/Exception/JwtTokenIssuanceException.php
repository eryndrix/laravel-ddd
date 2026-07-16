<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login\Exception;

final class JwtTokenIssuanceException extends \RuntimeException
{
    /**
     * @phpstan-param string $message
     * @phpstan-param int $code
     * @phpstan-param \Throwable|null $previous
     */
    public function __construct(
        string $message = 'Failed to issue JWT tokens.',
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
