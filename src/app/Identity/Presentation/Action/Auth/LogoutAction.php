<?php declare(strict_types=1);

namespace App\Identity\Presentation\Action\Auth;

use App\Shared\Presentation\Action;
use App\Identity\Application\Auth\Logout\LogoutCommand;
use App\Shared\Domain\Bus\CommandBusInterface;
use App\Identity\Presentation\Responder\Auth\LogoutResponder;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Route;
use App\Shared\Presentation\Response\ApiResponse;
use App\Shared\Application\Result\Result;
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
     *     \App\Identity\Application\Auth\Logout\LogoutProcess
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
        /** @phpstan-var \App\Identity\Infrastructure\Auth\UserAdapter $auth */
        $auth = $request->user();
        /** @phpstan-var \App\Identity\Domain\User $user */
        $user = $auth->unwrap();

        /** @phpstan-var Result<string, string> $result*/
        $result = $this->commandBus->send(
            command: new LogoutCommand(user: $user)
        );

        return $this->responder->respond(result: $result);
    }
}
