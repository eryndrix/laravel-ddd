<?php declare(strict_types=1);

namespace App\Identity\Presentation\Responder\Password;

use App\Shared\Presentation\Responder;
use App\Shared\Presentation\Response\ApiResponse;
use Illuminate\Http\Response as Status;
use App\Identity\Application\Password\Forgot\ForgotPasswordError;
use App\Shared\Application\Result\Result;

/**
 * @phpstan-extends Responder<string, ForgotPasswordError>
 */
final class ForgotPasswordResponder extends Responder
{
    /**
     * @phpstan-param Result<string, ForgotPasswordError> $result
     * @phpstan-return ApiResponse
     */
    public function respond(Result $result): ApiResponse
    {
        return $result->match(
            onSuccess: fn (string $message) => new ApiResponse(
                data: ['message' => __(key: $message)],
                status: Status::HTTP_OK
            ),
            onError: fn (ForgotPasswordError $error) => match ($error) {
                ForgotPasswordError::TooManyAttempts => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_TOO_MANY_REQUESTS
                ),
                ForgotPasswordError::InvalidEmailFormat => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_BAD_REQUEST
                ),
                ForgotPasswordError::EmailNotExists => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_UNAUTHORIZED
                ),
                default => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_INTERNAL_SERVER_ERROR
                )
            }
        );
    }
}
