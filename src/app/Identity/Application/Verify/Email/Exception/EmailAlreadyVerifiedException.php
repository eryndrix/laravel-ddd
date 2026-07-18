<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Verify\Exception;

final class EmailAlreadyVerifiedException extends \RuntimeException
{
    /**
     * @phpstan-param string $message
     * @phpstan-param int $code
     * @phpstan-param \Throwable|null $previous
     */
    public function __construct(
        string $message = 'Email is already verified.',
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
