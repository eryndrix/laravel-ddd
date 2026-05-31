<?php declare(strict_types=1);

namespace App\Shared\Domain\Bus;

/**
 * @phpstan-template TCommand of object
 * @phpstan-template THandler of object
 * @phpstan-template TResult
 */
interface CommandBusInterface
{
    /**
     * @phpstan-param TCommand $command
     * @phpstan-return TResult
     */
    public function send(object $command): mixed;
    
    /**
     * @phpstan-param array<
     *     class-string<TCommand>,
     *     class-string<THandler>
     * > $map
     * 
     * @phpstan-return void
     */
    public function register(array $map): void;
}
