<?php declare(strict_types=1);

namespace App\Shared\Domain;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

/**
 * @phpstan-template TData
 */
abstract class Event
{
    /**
     * Provides serialization capabilities for queued models.
     */
    use SerializesModels;

    /**
     * Supports interaction with WebSockets and broadcasting.
     */
    use InteractsWithSockets;

    /**
     * Enables dispatching events using Laravel's event system.
     */
    use Dispatchable;
}
