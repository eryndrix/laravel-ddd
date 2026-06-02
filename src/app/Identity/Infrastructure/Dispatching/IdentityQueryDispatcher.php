<?php declare(strict_types=1);

namespace App\Identity\Infrastructure\Dispatching;

use Illuminate\Support\ServiceProvider;
// use App\Identity\Application\CheckMe\CheckMeHandler;
// use App\Identity\Application\CheckMe\CheckMeQuery;
// use App\Identity\Application\Profile\Show\ShowProfileHandler;
// use App\Identity\Application\Profile\Show\ShowProfileQuery;
use App\Shared\Domain\Bus\QueryBusInterface;

final class IdentityQueryDispatcher extends ServiceProvider
{
    /**
     * @phpstan-var array<class-string, class-string>
     */
    private array $auth = [
        //CheckMeQuery::class => CheckMeHandler::class
    ];
    
    /**
     * @phpstan-var array<class-string, class-string>
     */
    private array $profile = [
        //ShowProfileQuery::class => ShowProfileHandler::class
    ];

    /**
     * @phpstan-param QueryBusInterface<object, object, mixed> $queryBus
     * @phpstan-return void
     */
    public function boot(QueryBusInterface $queryBus): void
    {
        $queryBus->register(map: [
            ...$this->auth,
            ...$this->profile
        ]);
    }
}
