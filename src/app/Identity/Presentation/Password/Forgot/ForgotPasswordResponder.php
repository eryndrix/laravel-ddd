<?php declare(strict_types=1);

namespace App\Identity\Presentation\Password\Forgot;

use App\Shared\Presentation\Responder;
use App\Shared\Presentation\Response\ApiResponse;
use App\Identity\Application\Password\Forgot\Exception\EmailNotFoundException;
use App\Identity\Application\Password\Forgot\Exception\PasswordResetLinkNotSentException;
use App\Shared\Application\Result\Result;
use Illuminate\Http\Response;

/**
 * @phpstan-extends Responder<null, \Throwable>
 */
final class ForgotPasswordResponder extends Responder
{
    /**
     * @phpstan-param Result<null, \Throwable> $result
     * @phpstan-return ApiResponse
     */
    public function respond(Result $result): ApiResponse
    {
        return $result->match(
            onSuccess: fn () => new ApiResponse(
                data: ['message' => __('identity.password.forgot.success')],
                status: Response::HTTP_OK
            ),
            onError: fn (\Throwable $e) => match (true) {
                $e instanceof \DomainException => new ApiResponse(
                    data: ['message' => $e->getMessage()],
                    status: Response::HTTP_BAD_REQUEST
                ),
                $e instanceof EmailNotFoundException,
                $e instanceof PasswordResetLinkNotSentException => new ApiResponse(
                    data: ['message' => __(key: 'identity.password.forgot.failed')],
                    status: Response::HTTP_INTERNAL_SERVER_ERROR
                ),
                default => new ApiResponse(
                    data: ['message' => __(key: 'identity.password.forgot.unknown')],
                    status: Response::HTTP_INTERNAL_SERVER_ERROR
                ),
            }
        );
    }
}
