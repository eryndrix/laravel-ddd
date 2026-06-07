<?php declare(strict_types=1);

namespace App\Privilege\Presentation\Responder;

use App\Shared\Presentation\Responder;
use App\Privilege\Presentation\RoleResource;
use Illuminate\Http\Response as Status;
use App\Privilege\Application\RoleSuccess;
use App\Privilege\Application\RoleError;
use App\Shared\Presentation\Response\ApiResponse;
use App\Shared\Application\Result\Result;

/**
 * @phpstan-extends Responder<
 *     RoleSuccess<\App\Privilege\Domain\Role>,
 *     RoleError
 * >
 */
final class ShowRoleResponder extends Responder
{
    /**
     * @phpstan-param Result<
     *     RoleSuccess<\App\Privilege\Domain\Role>,
     *     RoleError
     * > $result
     * 
     * @phpstan-return ApiResponse
     */
    public function respond(Result $result): ApiResponse
    {
        return $result->match(
            onSuccess: fn (RoleSuccess $success) => new ApiResponse(
                data: new RoleResource(resource: $success->result),
                status: Status::HTTP_OK
            ),
            onError: fn (RoleError $error) => match ($error) {
                RoleError::NotFound => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_NOT_FOUND
                ),
                default => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_INTERNAL_SERVER_ERROR
                )
            }
        );
    }
}
