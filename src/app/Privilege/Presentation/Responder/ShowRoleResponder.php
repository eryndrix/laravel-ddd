<?php declare(strict_types=1);

namespace App\Privilege\Presentation\Responder;

use Illuminate\Http\Response;
use App\Privilege\Presentation\RoleResource;
use App\Shared\Presentation\Response\ApiResponse;
use App\Privilege\Application\Error\RoleError;
use App\Shared\Presentation\Responder;
use App\Shared\Application\Result\Result;
use App\Privilege\Domain\Role;

/**
 * @phpstan-extends Responder<Role, string>
 */
final class ShowRoleResponder extends Responder
{
    /**
     * @phpstan-param Result<Role, string> $result
     * @phpstan-return ApiResponse
     */
    public function respond(Result $result): ApiResponse
    {
        return $result->match(
            onSuccess: fn (Role $role) => new ApiResponse(
                data: new RoleResource(resource: $role),
                status: Response::HTTP_OK
            ),
            onError: fn (string $error) => new ApiResponse(
                data: ['message' => $error],
                status: Response::HTTP_NOT_FOUND
            )
        );
    }
}
