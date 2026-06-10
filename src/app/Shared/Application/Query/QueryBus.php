<?php declare(strict_types=1);

namespace App\Shared\Application\Query;

use App\Shared\Domain\Bus\QueryBusInterface;
use Illuminate\Contracts\Bus\Dispatcher;

/**
 * @phpstan-implements QueryBusInterface<
 *     Query,
 *     \App\Shared\Application\Handler\Handler
 * >
 */
final class QueryBus implements QueryBusInterface
{
    /**
     * @phpstan-param Dispatcher $dispatcher
     */
    public function __construct(
        private Dispatcher $dispatcher
    ) {}
    
    /**
     * @phpstan-param Query $query
     * @phpstan-return mixed
     */
    public function ask(object $query): mixed
    {
        return $this->dispatcher->dispatch(
            command: $query
        );
    }
    
    /**
     * @phpstan-param array<
     *     class-string<Query>,
     *     class-string<
     *         \App\Shared\Application\Handler\Handler
     *     >
     * > $map
     * 
     * @phpstan-return void
     */
    public function register(array $map): void
    {
        $this->dispatcher->map(map: $map);
    }
}
