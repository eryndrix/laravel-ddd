<?php declare(strict_types=1);

namespace App\Identity\Presentation\Password\Forgot;

use App\Shared\Presentation\Action;
use App\Shared\Domain\Bus\CommandBusInterface;
use App\Identity\Application\Password\Forgot\ForgotPasswordCommand;
use App\Identity\Application\Password\Forgot\ForgotPasswordUseCase;
use Spatie\RouteAttributes\Attributes\Route;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Prefix;
use App\Shared\Presentation\Response\ApiResponse;
use App\Shared\Application\Result\Result;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: ['guest', 'throttle:3,5'])]
final class ForgotPasswordAction extends Action
{
    /**
     * @phpstan-var ForgotPasswordResponder
     */
    private readonly ForgotPasswordResponder $responder;

    /**
     * @phpstan-param CommandBusInterface<
     *     ForgotPasswordCommand,
     *     ForgotPasswordUseCase
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
    public function __invoke(
        ForgotPasswordRequest $request): ApiResponse
    {
        /** @phpstan-var Result<null, \Throwable> $result */
        $result = $this->commandBus->send(
            command: ForgotPasswordCommand::fromRequest(
                request: $request
            )
        );

        return $this->responder->respond(result: $result);
    }
}
