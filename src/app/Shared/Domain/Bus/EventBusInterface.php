<?php declare(strict_types=1);

namespace App\Shared\Domain\Bus;

/**
 * @phpstan-template TEvent of string|object
 * @phpstan-template TPayload
 * @phpstan-template TListener of string|callable
 */
interface EventBusInterface
{
    /**
     * @phpstan-param TEvent $event
     * @phpstan-param TPayload $payload
     * @phpstan-param bool $halt
     * 
     * @phpstan-return array<array-key, mixed>|null
     */
    public function dispatch(
        string|object $event,
        mixed $payload = [],
        bool $halt = false
    ): ?array;

    /**
     * @phpstan-param object|string $subscriber
     * @phpstan-return void
     */
    public function subscribe(object|string $subscriber): void;

    /**
     * @phpstan-param string|\Closure|array{string|\Closure} $events
     * @phpstan-param (string|\Closure)|array{string|\Closure}|null $listener
     * 
     * @phpstan-return void
     */
    public function listen(
        string|array|\Closure $events,
        string|array|\Closure|null $listener = null
    ): void;
    
    /**
     * @phpstan-param string $eventName
     * @phpstan-return bool
     */
    public function hasListeners(string $eventName): bool;
}
