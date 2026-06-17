<?php declare(strict_types=1);

namespace App\Identity\Presentation\Action\Password;

use App\Shared\Presentation\Action;
use App\Identity\Application\Password\Update\UpdatePasswordCommand;
use App\Shared\Domain\Bus\CommandBusInterface;
use App\Identity\Presentation\Request\UpdatePasswordRequest;
use App\Identity\Presentation\Responder\Password\UpdatePasswordResponder;
use Spatie\RouteAttributes\Attributes\Route;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Prefix;
use App\Shared\Presentation\Response\ApiResponse;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'auth:api')]
final class UpdatePasswordAction extends Action
{
    /**
     * @phpstan-var UpdatePasswordResponder
     */
    private readonly UpdatePasswordResponder $responder;

    /**
     * @phpstan-param CommandBusInterface<
     *     UpdatePasswordCommand,
     *     \App\Identity\Application\Password\Update\UpdatePasswordProcess
     * > $commandBus
     */
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
        $this->responder = new UpdatePasswordResponder();
    }

    /**
     * @phpstan-param UpdatePasswordRequest $request
     * @phpstan-return ApiResponse
     */
    #[Route(methods: 'PUT', uri: '/password/update')]
    public function __invoke(UpdatePasswordRequest $request): ApiResponse
    {
        /**
         * @phpstan-var \App\Shared\Application\Result\Result<
         *     string,
         *     \App\Identity\Application\Password\Update\UpdatePasswordError
         * > $result
         */
        $result = $this->commandBus->send(
            command: UpdatePasswordCommand::fromRequest(request: $request)
        );

        return $this->responder->respond(result: $result);
    }
}
