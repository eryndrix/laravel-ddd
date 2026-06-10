<?php declare(strict_types=1);

namespace App\Shared\Application\Handler;

/**
 * @phpstan-template TError
 */
final class HandlerException extends \Exception
{
    /**
     * @phpstan-var TError
     */
    private mixed $error;

    /**
     * @phpstan-param TError $error
     * @phpstan-param int $code
     * @phpstan-param \Throwable|null $previous
     */
    public function __construct(
        mixed $error,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $this->error = $error;

        if (is_object(value: $error) && method_exists(
            object_or_class: $error,
            method: 'message'
        )) {
            $result = $error->message();
            $message = is_string(value: $result)
                ? $result
                : '';
        } else {
            $message = is_string(value: $error)
                ? $error
                : '';
        }
        
        parent::__construct(
            message: $message,
            code: $code,
            previous: $previous
        );
    }

    /**
     * @phpstan-return TError
     */
    public function getError(): mixed
    {
        return $this->error;
    }
}
