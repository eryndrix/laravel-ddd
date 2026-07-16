<?php declare(strict_types=1);

namespace App\Identity\Presentation\Email\Verify;

use App\Shared\Presentation\Responder;
use App\Shared\Presentation\Response\WebResponse;
use App\Identity\Application\Email\Verify\Exception\EmailAlreadyVerifiedException;
use App\Identity\Application\Email\Verify\Exception\InvalidVerificationHashException;
use App\Identity\Application\Email\Verify\Exception\UserNotFoundException;
use App\Shared\Application\Result\Result;

/**
 * @phpstan-extends Responder<null, \Throwable>
 */
final class VerifyEmailResponder extends Responder
{
    /**
     * @phpstan-param Result<null, \Throwable> $result
     * @phpstan-return WebResponse
     */
    public function respond(Result $result): WebResponse
    {
        return $result->match(
            onSuccess: fn () => new WebResponse(
                view: 'email.verify-success',
                data: ['message' => __(key: 'identity.email.verify.success')]
            ),
            onError: fn (\Throwable $e) => match (true) {
                $e instanceof \DomainException => new WebResponse(
                    view: 'email.verify-failed',
                    data: ['message' => $e->getMessage()],
                ),
                $e instanceof EmailAlreadyVerifiedException => new WebResponse(
                    view: 'email.verify-failed',
                    data: ['message' => $e->getMessage()],
                ),
                $e instanceof InvalidVerificationHashException => new WebResponse(
                    view: 'email.verify-failed',
                    data: ['message' => $e->getMessage()],
                ),
                $e instanceof UserNotFoundException => new WebResponse(
                    view: 'email.verify-failed',
                    data: ['message' => __(key: 'identity.email.verify.failed')]
                ),
                default => new WebResponse(
                    view: 'email.verify-failed',
                    data: ['message' => __(key: 'identity.email.verify.unknown')]
                ),
            }
        );
    }
}
