<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Register\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Auth\Register\RegisterCommand;
use App\Shared\Domain\Bus\EventBusInterface;

final class DispatchRegisteredEventsHandler extends Handler
{
    /**
     * @phpstan-param EventBusInterface $eventBus
     */
    public function __construct(
        private readonly EventBusInterface $eventBus
    ) {}
    
    /**
     * @phpstan-param RegisterCommand $command
     * @phpstan-param \Closure(RegisterCommand):mixed $next
     *
     * @phpstan-return mixed
     */
    public function handle(
        RegisterCommand $command, \Closure $next): mixed
    {
        /* @phpstan-var \App\Identity\Domain\User $user */
        $user = $command->user;

        if (is_null(value: $user)) {
            return $next($command);
        }

        foreach ($user->release() as $event) {
            $this->eventBus->dispatch(event: $event);
        }

        return $next($command);
    }
}
