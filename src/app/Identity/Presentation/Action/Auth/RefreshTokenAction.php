<?php declare(strict_types=1);

namespace App\Identity\Presentation\Action\Auth;

use App\Shared\Presentation\Action;
use App\Identity\Application\Auth\RefreshToken\RefreshTokenCommand;
use App\Shared\Domain\Bus\CommandBusInterface;
use App\Identity\Presentation\Responder\Auth\RefreshTokenResponder;
use Spatie\RouteAttributes\Attributes\Route;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Prefix;
use App\Shared\Presentation\Response\ApiResponse;
use Illuminate\Http\Request;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'guest')]
final class RefreshTokenAction extends Action
{
	/**
     * @phpstan-var RefreshTokenResponder
     */
	private readonly RefreshTokenResponder $responder;

    /**
     * @phpstan-param CommandBusInterface<
     *     RefreshTokenCommand,
     *     \App\Identity\Application\Auth\RefreshToken\RefreshTokenProcess
     * > $commandBus
     */
	public function __construct(
		private readonly CommandBusInterface $commandBus
	) {
		$this->responder = new RefreshTokenResponder();
	}

    /**
     * @phpstan-param Request $request
     * @phpstan-return ApiResponse
     */
    #[Route(methods: 'POST', uri: '/token/refresh')]
    public function __invoke(Request $request): ApiResponse
    {
        /**
         * @phpstan-var \App\Shared\Application\Result\Result<
         *     array<string, mixed>,
         *     \App\Identity\Application\Auth\RefreshToken\RefreshTokenError
         * > $result */
        $result = $this->commandBus->send(
            command: RefreshTokenCommand::fromRequest(request: $request)
        );
        
        return $this->responder->respond(result: $result);
    }
}
