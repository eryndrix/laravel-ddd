<?php declare(strict_types=1);

namespace App\Identity\Presentation\Action\Email;

use App\Shared\Presentation\Action;
use App\Identity\Application\Email\Verify\VerifyEmailQuery;
use App\Shared\Domain\Bus\QueryBusInterface;
use App\Identity\Presentation\Request\VerifyEmailRequest;
use App\Identity\Presentation\Responder\Email\VerifyEmailResponder;
use App\Shared\Presentation\Response\WebResponse;

final class VerifyEmailAction extends Action
{
    /**
     * @phpstan-var VerifyEmailResponder
     */
    private readonly VerifyEmailResponder $responder;

    /**
     * @phpstan-param QueryBusInterface<
     *     VerifyEmailQuery,
     *     \App\Identity\Application\Email\Verify\VerifyEmailProcess
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
         * @phpstan-var \App\Shared\Application\Result\Result<
         *     string,
         *     \App\Identity\Application\Email\Verify\VerifyEmailError
         * > $result
         */
        $result = $this->queryBus->ask(
            query: VerifyEmailQuery::fromRequest(request: $request)
        );
        
        return $this->responder->respond(result: $result);
    }
}
