<?php declare(strict_types=1);

namespace App\Identity\Presentation\Responder\Auth;

use App\Shared\Presentation\Responder;
use App\Shared\Presentation\Response\ApiResponse;
use Illuminate\Http\Response as Status;
use App\Shared\Application\Result\Result;

/**
 * @phpstan-extends Responder<string, string>
 */
final class LogoutResponder extends Responder
{
    /**
     * @phpstan-param Result<string, string> $result
     * @phpstan-return ApiResponse
     */
    public function respond(Result $result): ApiResponse
    {
        return $result->match(
            onSuccess: fn (string $message) => new ApiResponse(
                data: ['message' => __(key: $message)],
                status: Status::HTTP_OK
            ),
            onError: fn (string $message) => new ApiResponse(
                data: ['message' => __(key: $message)],
                status: Status::HTTP_INTERNAL_SERVER_ERROR
            )
        );
    }
}
