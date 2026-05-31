<?php declare(strict_types=1);

namespace App\Privilege\Infrastructure\Dispatching;

use Illuminate\Support\ServiceProvider;
use App\Privilege\Application\Query\ListRoleQuery;
use App\Privilege\Application\Handler\ListRoleHandler;
use App\Privilege\Application\Query\ShowRoleQuery;
use App\Privilege\Application\Handler\ShowRoleHandler;
use App\Shared\Domain\Bus\QueryBusInterface;

/**
 * @phpstan-template TQuery of object
 * @phpstan-template THandler of object
 */
final class PrivilegeQueryDispatcher extends ServiceProvider
{
    /**
     * @phpstan-var array<class-string<TQuery>, class-string<THandler>>
     */
    private array $roles = [
        ListRoleQuery::class => ListRoleHandler::class,
        ShowRoleQuery::class => ShowRoleHandler::class,
    ];

    /**
     * @phpstan-param QueryBusInterface<object, object, mixed> $queryBus
     * @phpstan-return void
     */
    public function boot(QueryBusInterface $queryBus): void
    {
        $queryBus->register(map: [...$this->roles]);
    }
}
