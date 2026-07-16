<?php declare(strict_types=1);

namespace App\Identity\Presentation\Auth\Register;

use App\Shared\Presentation\Responder;
use App\Shared\Presentation\Response\ApiResponse;
use App\Identity\Application\Auth\Register\Exception\DefaultRoleNotFoundException;
use App\Identity\Application\Auth\Register\Exception\RoleIdRequiredException;
use App\Shared\Application\Result\Result;
use Illuminate\Http\Response;

/**
 * @phpstan-extends Responder<null, \Throwable>
 */
final class RegisterResponder extends Responder
{
    /**
     * @phpstan-param Result<null, \Throwable> $result
     * @phpstan-return ApiResponse
     */
    public function respond(Result $result): ApiResponse
    {
        return $result->match(
            onSuccess: fn () => new ApiResponse(
                data: ['message' => __(key: 'identity.register.success')],
                status: Response::HTTP_CREATED
            ),
            onError: fn (\Throwable $e) => match (true) {
                $e instanceof \DomainException => new ApiResponse(
                    data: ['message' => $e->getMessage()],
                    status: Response::HTTP_BAD_REQUEST
                ),
                $e instanceof DefaultRoleNotFoundException,
                $e instanceof RoleIdRequiredException => new ApiResponse(
                    data: ['message' => __(key: 'identity.register.failed')],
                    status: Response::HTTP_INTERNAL_SERVER_ERROR
                ),
                default => new ApiResponse(
                    data: ['message' => __(key: 'identity.register.unknown')],
                    status: Response::HTTP_INTERNAL_SERVER_ERROR
                ),
            }
        );
    }
}
