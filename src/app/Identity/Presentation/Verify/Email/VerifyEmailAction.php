<?php declare(strict_types=1);

namespace App\Identity\Presentation\Email\Verify;

use App\Shared\Presentation\Action;
use App\Identity\Application\Email\Verify\VerifyEmailQuery;
use App\Shared\Domain\Bus\QueryBusInterface;
use App\Shared\Presentation\Response\WebResponse;
use App\Shared\Application\Result\Result;

final class VerifyEmailAction extends Action
{
    /**
     * @phpstan-var VerifyEmailResponder
     */
    private readonly VerifyEmailResponder $responder;

    /**
     * @phpstan-param QueryBusInterface<
     *     VerifyEmailQuery,
     *     \App\Identity\Application\Email\Verify\VerifyEmailUseCase
     * > $queryBus
     */
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {
        $this->responder = new VerifyEmailResponder();
    }

    /**
     * @phpstan-param VerifyEmailRequest $request
     * @phpstan-return WebResponse
     */
    public function __invoke(VerifyEmailRequest $request): WebResponse
    {
        /**
         * @phpstan-var Result<null, \Throwable> $result
         */
        $result = $this->queryBus->ask(
            query: VerifyEmailQuery::fromRequest(request: $request)
        );
        
        return $this->responder->respond(result: $result);
    }
}
