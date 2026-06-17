<?php declare(strict_types=1);

namespace App\Identity\Presentation\Responder\Email;

use App\Shared\Presentation\Responder;
use App\Shared\Presentation\Response\WebResponse;
use Illuminate\Http\Response as Status;
use App\Identity\Application\Email\Verify\VerifyEmailError;
use App\Shared\Application\Result\Result;

/**
 * @phpstan-extends Responder<string, VerifyEmailError>
 */
final class VerifyEmailResponder extends Responder
{
    /**
     * @phpstan-param Result<string, VerifyEmailError> $result
     * @phpstan-return WebResponse
     */
    public function respond(Result $result): WebResponse
    {
        return $result->match(
            onSuccess: fn (string $message) => new WebResponse(
                view: 'email.verify-success',
                data: ['message' => __(key: $message)]
            ),
            onError: fn (VerifyEmailError $error) => match ($error) {
                VerifyEmailError::InvalidHash => new WebResponse(
                    view: 'email.verify-failed',
                    data: ['message' => $error->message()]
                ),
                VerifyEmailError::EmailVerified => new WebResponse(
                    view: 'email.verify-failed',
                    data: ['message' => $error->message()]
                ),
                default => new WebResponse(
                    view: 'email.verify-failed',
                    data: ['message' => $error->message()]
                ),
            }
        );
    }
}
