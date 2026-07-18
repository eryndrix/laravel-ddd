<?php declare(strict_types=1);

namespace App\Identity\Presentation\Profile\Update\Email;

use App\Shared\Presentation\Action;
use App\Identity\Application\Profile\Update\Email\UpdateEmailCommand;
use App\Identity\Application\Profile\Update\Email\UpdateEmailUseCase;
use App\Shared\Domain\Bus\CommandBusInterface;
use Spatie\RouteAttributes\Attributes\Route;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Prefix;
use App\Shared\Presentation\Response\ApiResponse;
use App\Shared\Application\Result\Result;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'auth:api')]
final class UpdateEmailAction extends Action
{
    /**
     * @phpstan-var UpdateEmailResponder
     */
    private readonly UpdateEmailResponder $responder;

    /**
     * @phpstan-param CommandBusInterface<
     *     UpdateEmailCommand,
     *     UpdateEmailUseCase
     * > $commandBus
     */
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
        $this->responder = new UpdateEmailResponder();
    }

    /**
     * @phpstan-param UpdateEmailRequest $request
     * @phpstan-return ApiResponse
     */
    #[Route(methods: 'PUT', uri: '/email/update')]
    public function __invoke(
        UpdateEmailRequest $request): ApiResponse
    {
        /** @phpstan-var Result<null, \Throwable> $result */
        $result = $this->commandBus->send(
            command: UpdateEmailCommand::fromRequest(
                request: $request
            )
        );
        
        return $this->responder->respond(result: $result);
    }
}
