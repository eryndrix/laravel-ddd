<?php declare(strict_types=1);

namespace App\Shared\Application;

use App\Shared\Domain\Bus\EventBusInterface;
use Illuminate\Contracts\Events\Dispatcher;

/**
 * @phpstan-implements EventBusInterface<string|object, mixed, string|\Closure>
 */
final class EventBus implements EventBusInterface
{
    /**
     * @phpstan-param Dispatcher $dispatcher
     */
    public function __construct(
        private Dispatcher $dispatcher
    ) {}

    /**
     * @phpstan-param string|object $event
     * @phpstan-param mixed $payload
     * @phpstan-param bool $halt
     * 
     * @phpstan-return array<array-key, mixed>|null
     */
    public function dispatch(
        string|object $event,
        mixed $payload = [],
        bool $halt = false): ?array
    {
        return $this->dispatcher->dispatch(
            event: $event,
            payload: $payload,
            halt: $halt
        );
    }

    /**
     * @phpstan-param object|string $subscriber
     * @phpstan-return void
     */
    public function subscribe(object|string $subscriber): void
    {
        $this->dispatcher->subscribe(
            subscriber: $subscriber
        );
    }

    /**
     * @phpstan-param string|\Closure|array{string|\Closure} $events
     * @phpstan-param (string|\Closure)|array{string|\Closure}|null $listener
     * 
     * @phpstan-return void
     */
    public function listen(
        string|array|\Closure $events,
        string|array|\Closure|null $listener = null): void
    {
        $this->dispatcher->listen(
            events: $events,
            listener: $listener
        );
    }

    /**
     * @phpstan-param string $eventName
     * @phpstan-return bool
     */
    public function hasListeners(string $eventName): bool
    {
        return $this->dispatcher->hasListeners(
            eventName: $eventName
        );
    }
}
