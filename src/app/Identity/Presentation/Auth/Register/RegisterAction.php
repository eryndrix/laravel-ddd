<?php declare(strict_types=1);

namespace App\Identity\Presentation\Auth\Register;

use App\Shared\Presentation\Action;
use App\Shared\Domain\Bus\CommandBusInterface;
use App\Identity\Application\Auth\Register\RegisterCommand;
use App\Identity\Application\Auth\Register\RegisterUseCase;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Prefix;
use App\Shared\Presentation\Response\ApiResponse;
use Spatie\RouteAttributes\Attributes\Route;
use App\Shared\Application\Result\Result;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'guest')]
final class RegisterAction extends Action
{
	/**
     * @phpstan-var RegisterResponder
     */
	private readonly RegisterResponder $responder;

    /**
	 * @phpstan-param CommandBusInterface<
	 *     RegisterCommand,
	 *     RegisterUseCase
	 * > $commandBus
	 */
	public function __construct(
		private readonly CommandBusInterface $commandBus
	) {
		$this->responder = new RegisterResponder();
	}

    /**
     * @phpstan-param RegisterRequest $request
     * @phpstan-return ApiResponse
     */
    #[Route(methods: 'POST', uri: '/register')]
	public function __invoke(RegisterRequest $request): ApiResponse
	{
        /** @phpstan-var Result<null, \Throwable> $result */
        $result = $this->commandBus->send(
            command: RegisterCommand::fromRequest(
            	request: $request
            )
        );
		
		return $this->responder->respond(result: $result);
	}
}
