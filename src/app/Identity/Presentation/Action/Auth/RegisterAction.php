<?php declare(strict_types=1);

namespace App\Identity\Presentation\Action\Auth;

use App\Shared\Presentation\Action;
use App\Identity\Application\Auth\Register\RegisterCommand;
use App\Shared\Domain\Bus\CommandBusInterface;
use App\Identity\Presentation\Request\RegisterRequest;
use App\Identity\Presentation\Responder\Auth\RegisterResponder;
use Spatie\RouteAttributes\Attributes\Route;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Prefix;
use App\Shared\Presentation\Response\ApiResponse;

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
	 *     \App\Identity\Application\Auth\Register\RegisterProcess
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
        /**
         * @phpstan-var \App\Shared\Application\Result\Result<
         *     \App\Identity\Application\Auth\Register\Output\RegisterSuccess,
         *     \App\Identity\Application\Auth\Register\Output\RegisterError
         * > $result */
        $result = $this->commandBus->send(
            command: RegisterCommand::fromRequest(request: $request)
        );
		
		return $this->responder->respond(result: $result);
	}
}
