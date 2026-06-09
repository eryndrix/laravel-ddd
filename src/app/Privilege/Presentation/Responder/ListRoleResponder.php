<?php declare(strict_types=1);

namespace App\Privilege\Presentation\Responder;

use App\Shared\Presentation\Responder;
use App\Privilege\Presentation\RoleCollection;
use Illuminate\Http\Response as Status;
use Eryndrix\Paginator\Paginator;
use App\Privilege\Application\RoleError;
use App\Shared\Presentation\Response\ApiResponse;
use App\Shared\Application\Result\Result;

/**
 * @phpstan-extends Responder<
 *     Paginator<\App\Privilege\Domain\Role>,
 *     RoleError
 * >
 */
final class ListRoleResponder extends Responder
{
    /**
     * @phpstan-param Result<
     *     Paginator<\App\Privilege\Domain\Role>,
     *     RoleError
     * > $result
     * 
     * @phpstan-return ApiResponse
     */
    public function respond(Result $result): ApiResponse
    {
        return $result->match(
            onSuccess: fn (Paginator $roles) => new ApiResponse(
                data: new RoleCollection(resource: $roles),
                status: Status::HTTP_OK
            ),
            onError: fn (RoleError $error) => match ($error) {
                RoleError::PerPageOutOfRange => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_BAD_REQUEST
                ),
                default => new ApiResponse(
                    data: ['message' => $error->message()],
                    status: Status::HTTP_INTERNAL_SERVER_ERROR
                )
            }
        );
    }
}
