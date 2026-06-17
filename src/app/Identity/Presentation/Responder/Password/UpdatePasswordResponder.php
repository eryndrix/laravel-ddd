<?php declare(strict_types=1);

namespace App\Identity\Presentation\Responder\Password;

use App\Shared\Presentation\Responder;
use App\Shared\Presentation\Response\ApiResponse;
use Illuminate\Http\Response as Status;
use App\Identity\Application\Password\Update\UpdatePasswordError;
use App\Shared\Application\Result\Result;

/**
 * @extends Responder<string, UpdatePasswordError>
 */
final class UpdatePasswordResponder extends Responder
{
    /**
     * @phpstan-param Result<string, UpdatePasswordError> $result
     * @phpstan-return ApiResponse
     */
    public function respond(Result $result): ApiResponse
    {
        return $result->match(
            onSuccess: fn (string $message) => new ApiResponse(
                data: ['message' => __(key: $message)],
                status: Status::HTTP_OK
            ),
            onError: fn (UpdatePasswordError $error) => match ($error) {
                UpdatePasswordError::Mismatch,
                UpdatePasswordError::InvalidPwdFormat => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_BAD_REQUEST
                ),
                UpdatePasswordError::Failed => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_UNAUTHORIZED
                ),
                default => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_INTERNAL_SERVER_ERROR
                ),
            }
        );
    }
}
