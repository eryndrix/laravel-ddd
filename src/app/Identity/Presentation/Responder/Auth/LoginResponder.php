<?php declare(strict_types=1);

namespace App\Identity\Presentation\Responder\Auth;

use App\Shared\Presentation\Responder;
use App\Shared\Presentation\Response\ApiResponse;
use App\Identity\Presentation\JwtTokenPair;
use Illuminate\Http\Response as Status;
use App\Identity\Application\Auth\Login\LoginError;
use App\Shared\Application\Result\Result;

/**
 * @phpstan-extends Responder<array<string, mixed>, LoginError>
 */
final class LoginResponder extends Responder
{
    /**
     * @phpstan-param Result<array<string, mixed>, LoginError> $result
     * @phpstan-return ApiResponse
     */
    public function respond(Result $result): ApiResponse
    {
        return $result->match(
            onSuccess: fn (array $token) => new ApiResponse(
                data: JwtTokenPair::fromArray(
                    data: $token // @phpstan-ignore argument.type
                )->toArray(),
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
