<?php declare(strict_types=1);

namespace App\Identity\Presentation\Auth\Check;

use App\Shared\Presentation\Responder;
use App\Shared\Presentation\Response\ApiResponse;
use App\Shared\Application\Exception\UserNotFoundException;
use App\Shared\Application\Result\Result;
use App\Identity\Application\Auth\Check\UserData;
use Illuminate\Http\Response;

/**
 * @phpstan-extends Responder<UserData, \Throwable>
 */
final class UserResponder extends Responder
{
    /**
     * @phpstan-param Result<UserData, \Throwable> $result
     * @phpstan-return ApiResponse
     */
    public function respond(Result $result): ApiResponse
    {
        return $result->match(
            onSuccess: fn (UserData $data) => new ApiResponse(
                data: new UserResource(resource: $data),
                status: Response::HTTP_OK
            ),
            onError: fn (\Throwable $e) => match (true) {
                $e instanceof \DomainException => new ApiResponse(
                    data: ['message' => $e->getMessage()],
                    status: Response::HTTP_BAD_REQUEST
                ),
                $e instanceof UserNotFoundException => new ApiResponse(
                    data: ['message' => __(key: 'identity.register.failed')],
                    status: Response::HTTP_UNAUTHORIZED
                ),
                default => new ApiResponse(
                    data: ['message' => __(key: 'identity.register.unknown')],
                    status: Response::HTTP_INTERNAL_SERVER_ERROR
                ),
            }
        );
    }
}
