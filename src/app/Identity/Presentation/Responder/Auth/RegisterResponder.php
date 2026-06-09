<?php declare(strict_types=1);

namespace App\Identity\Presentation\Responder\Auth;

use App\Shared\Presentation\Responder;
use App\Shared\Presentation\Response\ApiResponse;
use Illuminate\Http\Response as Status;
use App\Identity\Application\Auth\Register\RegisterError;
use App\Shared\Application\Result\Result;

/**
 * @phpstan-extends Responder<string, RegisterError>
 */
final class RegisterResponder extends Responder
{
    /**
     * @phpstan-param Result<string, RegisterError> $result
     * @phpstan-return ApiResponse
     */
    public function respond(Result $result): ApiResponse
    {
        return $result->match(
            onSuccess: fn (string $message) => new ApiResponse(
                data: ['message' => __(key: $message)],
                status: Status::HTTP_CREATED
            ),
            onError: fn (RegisterError $error) => match ($error) {
                RegisterError::InvalidCredentials => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_BAD_REQUEST
                ),
                default => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_INTERNAL_SERVER_ERROR
                ),
            }
        );
    }
}
