<?php declare(strict_types=1);

namespace App\Identity\Application\Profile\Update\Email\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Profile\Update\Email\UpdateEmailCommand;
use App\Shared\Application\Exception\UserNotFoundException;
use App\Shared\Domain\Bus\EventBusInterface;

final class DispatchVerificationLinkHandler extends Handler
{
    /**
     * @phpstan-param EventBusInterface $eventBus
     */
    public function __construct(
        private readonly EventBusInterface $eventBus
    ) {}
    
    /**
     * @phpstan-param UpdateEmailCommand $command
     * @phpstan-param \Closure(UpdateEmailCommand):mixed $next
     *
     * @phpstan-return mixed
     * 
     * @throws UserNotFoundException
     */
    public function handle(
        UpdateEmailCommand $command, \Closure $next): mixed
    {
        /* @phpstan-var \App\Identity\Domain\User $user */
        $user = $command->user;

        if (is_null(value: $user)) {
            throw new UserNotFoundException();
        }

        foreach ($user->release() as $event) {
            $this->eventBus->dispatch(event: $event);
        }

        return $next($command);
    }
}
