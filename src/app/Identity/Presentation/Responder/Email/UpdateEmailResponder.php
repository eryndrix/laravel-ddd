<?php declare(strict_types=1);

namespace App\Identity\Presentation\Responder\Email;

use App\Shared\Presentation\Responder;
use App\Shared\Presentation\Response\ApiResponse;
use Illuminate\Http\Response as Status;
use App\Identity\Application\Email\Update\UpdateEmailError;
use App\Shared\Application\Result\Result;

/**
 * @phpstan-extends Responder<string, UpdateEmailError>
 */
final class UpdateEmailResponder extends Responder
{
    /**
     * @phpstan-param Result<string, UpdateEmailError> $result
     * @phpstan-return ApiResponse
     */
    public function respond(Result $result): ApiResponse
    {
        return $result->match(
            onSuccess: fn (string $message) => new ApiResponse(
                data: ['message' => __(key: $message)],
                status: Status::HTTP_OK
            ),
            onError: fn (UpdateEmailError $error) => match ($error) {
                UpdateEmailError::InvalidEmailFormat => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_BAD_REQUEST
                ),
                UpdateEmailError::EmailSameAsCurrent => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_CONFLICT
                ),
                default => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_INTERNAL_SERVER_ERROR
                )
            }
        );
    }
}
