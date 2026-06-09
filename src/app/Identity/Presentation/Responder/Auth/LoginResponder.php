<?php declare(strict_types=1);

namespace App\Identity\Presentation\Responder\Auth;

use App\Shared\Presentation\Responder;
use App\Identity\Presentation\Resource\TokenResource;
use App\Shared\Presentation\Response\ApiResponse;
use Illuminate\Http\Response as Status;
use App\Identity\Application\Auth\Login\LoginError;
use App\Shared\Application\Result\Result;

/**
 * @phpstan-extends Responder<array<string, int>, LoginError>
 */
final class LoginResponder extends Responder
{
    /**
     * @phpstan-param Result<array<string, int>, LoginError> $result
     * @phpstan-return ApiResponse
     */
    public function respond(Result $result): ApiResponse
    {
        return $result->match(
            onSuccess: fn (array $token) => new ApiResponse(
                data: new TokenResource(resource: $token),
                status: Status::HTTP_OK
            ),
            onError: fn (LoginError $error) => match ($error) {
                LoginError::TooManyAttempts => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_TOO_MANY_REQUESTS
                ),
                LoginError::InvalidCredentials => new ApiResponse(
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
