<?php declare(strict_types=1);

namespace App\Identity\Presentation\Responder\Auth;

use App\Shared\Presentation\Responder;
use App\Identity\Presentation\Resource\TokenResource;
use App\Shared\Presentation\Response\ApiResponse;
use Illuminate\Http\Response as Status;
use App\Identity\Application\Auth\Login\Output\LoginSuccess;
use App\Identity\Application\Auth\Login\Output\LoginError;
use App\Shared\Application\Result\Result;

/**
 * @phpstan-extends Responder<LoginSuccess, LoginError>
 */
final class LoginResponder extends Responder
{
    /**
     * @phpstan-param Result<LoginSuccess, LoginError> $result
     * @phpstan-return ApiResponse
     */
    public function respond(Result $result): ApiResponse
    {
        return $result->match(
            onSuccess: fn (LoginSuccess $success) => new ApiResponse(
                data: new TokenResource(resource: $success->result),
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
