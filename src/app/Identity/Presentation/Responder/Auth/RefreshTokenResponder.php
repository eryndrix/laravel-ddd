<?php declare(strict_types=1);

namespace App\Identity\Presentation\Responder\Auth;

use App\Shared\Presentation\Responder;
use App\Shared\Presentation\Response\ApiResponse;
use Illuminate\Http\Response as Status;
use App\Identity\Application\Auth\RefreshToken\RefreshTokenError;
use App\Identity\Presentation\JwtTokenPair;
use App\Shared\Application\Result\Result;

/**
 * @phpstan-extends Responder<array<string, mixed>, RefreshTokenError>
 */
final class RefreshTokenResponder extends Responder
{
    /**
     * @phpstan-param Result<array<string, mixed>, RefreshTokenError> $result
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
             onError: fn (RefreshTokenError $error) => match ($error) {
                RefreshTokenError::InvalidTokenFormat,
                RefreshTokenError::MissingAbility => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_BAD_REQUEST
                ),
                RefreshTokenError::TokenNotExists,
                RefreshTokenError::TokenRevoked => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_UNAUTHORIZED
                ),
                RefreshTokenError::TokenExpired => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_FORBIDDEN
                ),
                default => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_INTERNAL_SERVER_ERROR
                )
            }
        );
    }
}
