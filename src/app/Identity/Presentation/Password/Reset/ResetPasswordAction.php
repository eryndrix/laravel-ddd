<?php declare(strict_types=1);

namespace App\Identity\Presentation\Password\Reset;

use App\Shared\Presentation\Action;
use App\Shared\Domain\Bus\CommandBusInterface;
use App\Identity\Application\Password\Reset\ResetPasswordCommand;
use App\Identity\Application\Password\Reset\ResetPasswordUseCase;
use Spatie\RouteAttributes\Attributes\Route;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Prefix;
use App\Shared\Presentation\Response\ApiResponse;
use App\Shared\Application\Result\Result;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: ['guest', 'throttle:3,5'])]
final class ResetPasswordAction extends Action
{
    /**
     * @phpstan-var ResetPasswordResponder
     */
    private readonly ResetPasswordResponder $responder;

    /**
     * @phpstan-param CommandBusInterface<
     *     ResetPasswordCommand,
     *     ResetPasswordUseCase
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
    public function __invoke(
        ResetPasswordRequest $request): ApiResponse
    {
        /** @phpstan-var Result<null, \Throwable> $result */
        $result = $this->commandBus->send(
            command: ResetPasswordCommand::fromRequest(
                request: $request
            )
        );

        return $this->responder->respond(result: $result);
    }
}
