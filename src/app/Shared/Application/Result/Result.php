<?php declare(strict_types=1);

namespace App\Shared\Application\Result;

/**
 * @phpstan-template TValue
 * @phpstan-template-covariant TError
 */
abstract class Result
{
    /** @phpstan-use MapHelpers<TValue, TError> */
    use MapHelpers;

    /**
     * @phpstan-param TValue|null $value
     * @phpstan-param TError|null $error
     */
    protected function __construct(
        public readonly mixed $value = null,
        public readonly mixed $error = null
    ) {}

    /**
     * @template TNewValue
     * @phpstan-param TNewValue $value
     * @phpstan-return Success<TNewValue>
     */
    public static function success(mixed $value): Success
    {
        return new Success(value: $value);
    }

    /**
     * @template TNewError
     * @phpstan-param TNewError $error
     * @phpstan-return Failure<TNewError>
     */
    public static function failure(mixed $error): Failure
    {
        return new Failure(error: $error);
    }

    /**
     * @phpstan-return bool
     */
    abstract public function isSuccess(): bool;

    /**
     * @phpstan-return bool
     */
    public function isFailure(): bool
    {
        return !$this->isSuccess();
    }
}
