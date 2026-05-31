<?php declare(strict_types=1);

namespace App\Shared\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @phpstan-template TEvent of Event
 */
#[ORM\MappedSuperclass]
abstract class AggregateRoot
{
    /**
     * @phpstan-var list<TEvent>
     */
    private array $events = [];

    /**
     * @phpstan-param TEvent $event
     * @phpstan-return void
     */
    public function record(Event $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @phpstan-return list<TEvent>
     */
    public function release(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}
