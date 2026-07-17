<?php declare(strict_types=1);

namespace App\Identity\Presentation\Auth\Token\Refresh;

use App\Shared\Presentation\Action;
use App\Identity\Application\Auth\Token\Refresh\RefreshTokenCommand;
use App\Shared\Domain\Bus\CommandBusInterface;
use Spatie\RouteAttributes\Attributes\Route;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Prefix;
use App\Shared\Presentation\Response\ApiResponse;
use App\Shared\Application\Result\Result;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: ['guest', 'throttle:3,5'])]
final class RefreshTokenAction extends Action
{
	/**
     * @phpstan-var RefreshTokenResponder
     */
	private readonly RefreshTokenResponder $responder;

    /**
     * @phpstan-param CommandBusInterface<
     *     RefreshTokenCommand,
     *     \App\Identity\Application\Auth\Token\Refresh\RefreshTokenUseCase
     * > $commandBus
     */
	public function __construct(
		private readonly CommandBusInterface $commandBus
	) {
		$this->responder = new RefreshTokenResponder();
	}

    /**
     * @phpstan-param RefreshTokenRequest $request
     * @phpstan-return ApiResponse
     */
    #[Route(methods: 'POST', uri: '/token/refresh')]
    public function __invoke(RefreshTokenRequest $request): ApiResponse
    {
        /**
         * @phpstan-var Result<
         *     \App\Identity\Application\Auth\Token\TokenData,
         *     \Throwable
         * > $result */
        $result = $this->commandBus->send(
            command: RefreshTokenCommand::fromRequest(request: $request)
        );
        
        return $this->responder->respond(result: $result);
    }
}
