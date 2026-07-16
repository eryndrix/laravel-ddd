<?php declare(strict_types=1);

namespace App\Identity\Presentation\Action\Password;

use App\Shared\Presentation\Action;
use App\Identity\Application\Password\Reset\ResetPasswordCommand;
use App\Shared\Domain\Bus\CommandBusInterface;
use App\Identity\Presentation\Request\Password\ResetPasswordRequest;
use App\Identity\Presentation\Responder\Password\ResetPasswordResponder;
use Spatie\RouteAttributes\Attributes\Route;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Prefix;
use App\Shared\Presentation\Response\ApiResponse;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'guest')]
final class ResetPasswordAction extends Action
{
    /**
     * @phpstan-var ResetPasswordResponder
     */
    private readonly ResetPasswordResponder $responder;

    /**
     * @phpstan-param CommandBusInterface<
     *     ResetPasswordCommand,
     *     \App\Identity\Application\Password\Reset\ResetPasswordProcess
     * > $commandBus
     */
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
        $this->responder = new ResetPasswordResponder();
    }

    /**
     * @phpstan-param ResetPasswordRequest $request
     * @phpstan-return ApiResponse
     */
    #[Route(methods: 'POST', uri: '/password/reset')]
    public function __invoke(ResetPasswordRequest $request): ApiResponse
    {
        /**
         * @phpstan-var \App\Shared\Application\Result\Result<
         *     string,
         *     \App\Identity\Application\Password\Reset\ResetPasswordError
         * > $result
         */
        $result = $this->commandBus->send(
            command: ResetPasswordCommand::fromRequest(request: $request)
        );

        return $this->responder->respond(result: $result);
    }
}
