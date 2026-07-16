<?php declare(strict_types=1);

namespace App\Identity\Presentation\Action\Password;

use App\Shared\Presentation\Action;
use App\Identity\Application\Password\Forgot\ForgotPasswordCommand;
use App\Shared\Domain\Bus\CommandBusInterface;
use App\Identity\Presentation\Request\Password\ForgotPasswordRequest;
use App\Identity\Presentation\Responder\Password\ForgotPasswordResponder;
use Spatie\RouteAttributes\Attributes\Route;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Prefix;
use App\Shared\Presentation\Response\ApiResponse;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'guest')]
final class ForgotPasswordAction extends Action
{
    /**
     * @phpstan-var ForgotPasswordResponder
     */
    private readonly ForgotPasswordResponder $responder;

    /**
     * @phpstan-param CommandBusInterface<
     *     ForgotPasswordCommand,
     *     \App\Identity\Application\Password\Forgot\ForgotPasswordProcess
     * > $commandBus
     */
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
        $this->responder = new ForgotPasswordResponder();
    }

    /**
     * @phpstan-param ForgotPasswordRequest $request
     * @phpstan-return ApiResponse
     */
    #[Route(methods: 'POST', uri: '/password/email')]
    public function __invoke(ForgotPasswordRequest $request): ApiResponse
    {
        /**
         * @phpstan-var \App\Shared\Application\Result\Result<
         *     string,
         *     \App\Identity\Application\Password\Forgot\ForgotPasswordError
         * > $result
         */
        $result = $this->commandBus->send(
            command: ForgotPasswordCommand::fromRequest(request: $request)
        );

        return $this->responder->respond(result: $result);
    }
}
