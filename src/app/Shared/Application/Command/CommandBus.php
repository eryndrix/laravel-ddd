<?php declare(strict_types=1);

namespace App\Shared\Application\Command;

use App\Shared\Domain\Bus\CommandBusInterface;
use Illuminate\Contracts\Bus\Dispatcher;

/**
 * @phpstan-implements CommandBusInterface<
 *     Command,
 *     \App\Shared\Application\Handler
 * >
 */
final class CommandBus implements CommandBusInterface
{
    /**
     * @phpstan-param Dispatcher $dispatcher
     */
    public function __construct(
        private Dispatcher $dispatcher
    ) {}

    /**
     * @phpstan-param Command $command
     * @phpstan-return mixed
     */
    public function send(object $command): mixed
    {
        return $this->dispatcher->dispatch(
            command: $command
        );
    }
    
    /**
     * @phpstan-param array<
     *     class-string<Command>,
     *     class-string<\App\Shared\Application\Handler>
     * > $map
     * 
     * @phpstan-return void
     */
    public function register(array $map): void
    {
        $this->dispatcher->map(map: $map);
    }
}
