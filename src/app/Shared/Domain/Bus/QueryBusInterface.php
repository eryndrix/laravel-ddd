<?php declare(strict_types=1);

namespace App\Shared\Domain\Bus;

/**
 * @phpstan-template TQuery of object
 * @phpstan-template THandler of object
 * @phpstan-template TResult
 */
interface QueryBusInterface
{
    /**
     * @phpstan-param TQuery $query
     * @phpstan-return TResult
     */
    public function ask(object $query): mixed;

    /**
     * @phpstan-param array<
     *     class-string<TQuery>,
     *     class-string<THandler>
     * > $map
     * 
     * @phpstan-return void
     */
    public function register(array $map): void;
}
