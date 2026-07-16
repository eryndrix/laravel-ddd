<?php declare(strict_types=1);

namespace App\Identity\Presentation\Auth\User;

use App\Shared\Presentation\Action;
use App\Identity\Application\Auth\User\UserQuery;
use App\Shared\Domain\Bus\QueryBusInterface;
use App\Identity\Domain\Access\Auth\UserAdapterInterface;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Route;
use App\Shared\Presentation\Response\ApiResponse;
use App\Shared\Application\Result\Result;
use Illuminate\Http\Request;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'auth:api')]
final class UserAction extends Action
{
    /**
     * @phpstan-var UserResponder
     */
    private readonly UserResponder $responder;

    /**
     * @phpstan-param QueryBusInterface<
     *     UserQuery<\App\Identity\Domain\User>,
     *     \App\Identity\Application\Auth\User\UserUseCase
     * > $queryBus
     */
    public function __construct(
        private QueryBusInterface $queryBus
    ) {
        $this->responder = new UserResponder();
    }

    /**
     * @phpstan-param Request $request
     * @phpstan-return ApiResponse
     */
    #[Route(methods: 'GET', uri: '/user')]
    public function __invoke(Request $request): ApiResponse
    {
        /** @phpstan-var UserAdapterInterface $auth */
        $auth = $request->user();
        /** @phpstan-var \App\Identity\Domain\User $user */
        $user = $auth->unwrap();

        /**
         * @phpstan-var Result<
         *     \App\Identity\Application\Auth\User\UserData,
         *     \Throwable
         * > $result
         */
        $result = $this->queryBus->ask(
            query: new UserQuery(user: $user)
        );

        return $this->responder->respond(result: $result);
    }
}
