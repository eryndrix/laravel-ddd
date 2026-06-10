<?php declare(strict_types=1);

namespace App\Identity\Presentation\Responder\Auth;

use App\Shared\Presentation\Responder;
use App\Identity\Presentation\Resource\TokenResource;
use App\Shared\Presentation\Response\ApiResponse;
use Illuminate\Http\Response as Status;
use App\Identity\Application\Auth\Token\RefreshTokenError;
use App\Shared\Application\Result\Result;

/**
 * @phpstan-extends Token<array<string, int>, RefreshTokenError>
 */
final class TokenResponder extends Responder
{
    /**
     * @phpstan-param Result<array<string, int>, RefreshTokenError> $result
     * @phpstan-return ApiResponse
     */
    public function respond(Result $result): ApiResponse
    {
        return $result->match(
            onSuccess: fn ($token) => new ApiResponse(
                data: new TokenResource(resource: $token),
                status: Status::HTTP_OK
            ),
             onError: fn (RefreshTokenError $error) => match ($error) {
                RefreshTokenError::InvalidToken => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_UNAUTHORIZED // 401
                ),
                RefreshTokenError::Expired => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_FORBIDDEN // 403
                ),
                RefreshTokenError::MissingAbility => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_BAD_REQUEST // 400
                ),
                default => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_INTERNAL_SERVER_ERROR
                )
            }
        );
    }
}
