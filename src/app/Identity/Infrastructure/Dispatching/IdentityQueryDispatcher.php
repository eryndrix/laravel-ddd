<?php declare(strict_types=1);

namespace App\Identity\Infrastructure\Dispatching;

use Illuminate\Support\ServiceProvider;
use App\Identity\Application\Email\Verify\VerifyEmailQuery;
use App\Identity\Application\Email\Verify\VerifyEmailUseCase;
use App\Identity\Application\Auth\Check\UserQuery;
use App\Identity\Application\Auth\Check\UserUseCase;
// use App\Identity\Application\Profile\Show\ShowProfileHandler;
// use App\Identity\Application\Profile\Show\ShowProfileQuery;
use App\Shared\Domain\Bus\QueryBusInterface;

final class IdentityQueryDispatcher extends ServiceProvider
{
    /**
     * @phpstan-var array<class-string, class-string>
     */
    private array $auth = [
        UserQuery::class => UserUseCase::class
    ];
    
    /**
     * @phpstan-var array<class-string, class-string>
     */
    private array $profile = [
        //ShowProfileQuery::class => ShowProfileHandler::class
    ];
    
    /**
     * @phpstan-var array<class-string, class-string>
     */
    private array $email = [
        VerifyEmailQuery::class => VerifyEmailUseCase::class
    ];

    /**
     * @phpstan-param QueryBusInterface<object, object> $queryBus
     * @phpstan-return void
     */
    public function boot(QueryBusInterface $queryBus): void
    {
        $queryBus->register(map: [
            ...$this->auth,
            ...$this->profile,
            ...$this->email
        ]);
    }
}
