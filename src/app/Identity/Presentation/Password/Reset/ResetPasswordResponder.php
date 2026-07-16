<?php declare(strict_types=1);

namespace App\Identity\Presentation\Responder\Password;

use App\Shared\Presentation\Responder;
use App\Shared\Presentation\Response\ApiResponse;
use Illuminate\Http\Response as Status;
use App\Identity\Application\Password\Reset\ResetPasswordError;
use App\Shared\Application\Result\Result;

/**
 * @phpstan-extends Responder<string, ResetPasswordError>
 */
final class ResetPasswordResponder extends Responder
{
    /**
     * @phpstan-param Result<string, ResetPasswordError> $result
     * @phpstan-return ApiResponse
     */
    public function respond(Result $result): ApiResponse
    {
        return $result->match(
            onSuccess: fn (string $message) => new ApiResponse(
                data: ['message' => __(key: $message)],
                status: Status::HTTP_OK
            ),
            onError: fn (ResetPasswordError $error) => match ($error) {
                ResetPasswordError::InvalidEmail,
                ResetPasswordError::InvalidPwdFormat => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_BAD_REQUEST
                ),
                ResetPasswordError::Unknown => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_INTERNAL_SERVER_ERROR
                ),
            }
        );
    }
}
