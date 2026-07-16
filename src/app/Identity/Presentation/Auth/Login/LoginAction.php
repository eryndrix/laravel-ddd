<?php declare(strict_types=1);

namespace App\Identity\Presentation\Auth\Login;

use App\Shared\Presentation\Action;
use App\Identity\Application\Auth\Login\LoginCommand;
use App\Shared\Domain\Bus\CommandBusInterface;
use Spatie\RouteAttributes\Attributes\Route;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Prefix;
use App\Shared\Presentation\Response\ApiResponse;
use App\Shared\Application\Result\Result;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: ['guest', 'throttle:3,5'])]
final class LoginAction extends Action
{
	/**
     * @phpstan-var LoginResponder
     */
	private readonly LoginResponder $responder;

    /**
     * @phpstan-param CommandBusInterface<
     *     LoginCommand,
     *     \App\Identity\Application\Auth\Login\LoginUseCase
     * > $commandBus
     */
	public function __construct(
		private readonly CommandBusInterface $commandBus
	) {
		$this->responder = new LoginResponder();
	}

    /**
     * @phpstan-param LoginRequest $request
     * @phpstan-return ApiResponse
     */
    #[Route(methods: 'POST', uri: '/login')]
    public function __invoke(LoginRequest $request): ApiResponse
    {
        /**
         * @phpstan-var Result<
         *     \App\Identity\Application\Auth\Token\TokenData,
         *     \Throwable
         * > $result */
        $result = $this->commandBus->send(
            command: LoginCommand::fromRequest(request: $request)
        );
        
        return $this->responder->respond(result: $result);
    }
}
