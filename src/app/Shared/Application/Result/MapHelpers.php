<?php declare(strict_types=1);

namespace App\Shared\Application\Result;

/**
 * @phpstan-template TValue
 * @phpstan-template-covariant TError
 * @phpstan-require-extends Result<TValue, TError>
 */
trait MapHelpers
{
    /**
     * @template TNewValue
     * @phpstan-param callable(TValue): TNewValue $mapper
     * @phpstan-return Result<TNewValue, TError>
     */
    public function map(callable $mapper): Result
    {
        if (!$this->isSuccess()) {
            return $this;
        }
        
        /** @phpstan-var TValue $value */
        $value = $this->value;
        return static::success(value: $mapper($value));
    }
    
    /**
     * @template TSuperError of TError
     * @phpstan-param callable(TError): TSuperError $mapper
     * @phpstan-return Result<TValue, TSuperError>
     */
    public function mapError(callable $mapper): Result
    {
        if ($this->isSuccess()) {
            return $this;
        }
        
        /** @phpstan-var TError $error */
        $error = $this->error;
        return static::failure(error: $mapper($error));
    }
    
    /**
     * @template TResult
     * @phpstan-param callable(TValue): TResult $onSuccess
     * @phpstan-param callable(TError): TResult $onError
     * @phpstan-return TResult
     */
    public function match(
        callable $onSuccess, callable $onError): mixed
    {
        if ($this->isSuccess()) {
            /** @phpstan-var TValue $value */
            $value = $this->value;
            return $onSuccess($value);
        }

        /** @phpstan-var TError $error */
        $error = $this->error;
        return $onError($error);
    }
}
