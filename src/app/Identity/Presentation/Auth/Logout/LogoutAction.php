<?php declare(strict_types=1);

namespace App\Identity\Presentation\Auth\Logout;

use App\Shared\Presentation\Action;
use App\Identity\Application\Auth\Logout\LogoutCommand;
use App\Shared\Domain\Bus\CommandBusInterface;
use App\Identity\Application\Auth\Logout\LogoutError;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Route;
use App\Identity\Domain\Access\Auth\UserAdapterInterface;
use App\Shared\Application\Result\Result;
use App\Shared\Presentation\Response\ApiResponse;
use Illuminate\Http\Request;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'auth:api')]
final class LogoutAction extends Action
{
	/**
     * @phpstan-var LogoutResponder
     */
    private readonly LogoutResponder $responder;

    /**
     * @phpstan-param CommandBusInterface<
     *     LogoutCommand,
     *     \App\Identity\Application\Auth\Logout\LogoutUseCase
     * > $commandBus
     */
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
        $this->responder = new LogoutResponder();
    }

    /**
     * @phpstan-param Request $request
     * @phpstan-return ApiResponse
     */
    #[Route(methods: 'POST', uri: '/logout')]
    public function __invoke(Request $request): ApiResponse
    {
        /** @phpstan-var UserAdapterInterface $auth */
        $auth = $request->user();
        /** @phpstan-var \App\Identity\Domain\User $user */
        $user = $auth->unwrap();

        /** @phpstan-var Result<null, \Throwable> $result*/
        $result = $this->commandBus->send(
            command: new LogoutCommand(user: $user)
        );

        return $this->responder->respond(result: $result);
    }
}
