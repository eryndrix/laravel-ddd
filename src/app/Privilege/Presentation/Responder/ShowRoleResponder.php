<?php declare(strict_types=1);

namespace App\Privilege\Presentation\Responder;

use App\Shared\Presentation\Responder;
use App\Privilege\Presentation\RoleResource;
use Illuminate\Http\Response as Status;
use App\Privilege\Domain\Role;
use App\Privilege\Application\RoleError;
use App\Shared\Presentation\Response\ApiResponse;
use App\Shared\Application\Result\Result;

/**
 * @phpstan-extends Responder<Role, RoleError>
 */
final class ShowRoleResponder extends Responder
{
    /**
     * @phpstan-param Result<Role, RoleError> $result
     * @phpstan-return ApiResponse
     */
    public function respond(Result $result): ApiResponse
    {
        return $result->match(
            onSuccess: fn (Role $role) => new ApiResponse(
                data: new RoleResource(resource: $role),
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
