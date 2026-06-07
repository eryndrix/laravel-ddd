<?php declare(strict_types=1);

namespace App\Privilege\Presentation\Responder;

use App\Shared\Presentation\Responder;
use App\Privilege\Presentation\RoleCollection;
use App\Privilege\Application\RoleSuccess;
use App\Privilege\Application\RoleError;
use Illuminate\Http\Response as Status;
use App\Shared\Presentation\Response\ApiResponse;
use App\Shared\Application\Result\Result;

/**
 * @phpstan-extends Responder<
 *     RoleSuccess<\Eryndrix\Paginator\Paginator<\App\Privilege\Domain\Role>>,
 *     RoleError
 * >
 */
final class ListRoleResponder extends Responder
{
    /**
     * @phpstan-param Result<
     *     RoleSuccess<\Eryndrix\Paginator\Paginator<\App\Privilege\Domain\Role>>,
     *     RoleError
     * > $result
     * 
     * @phpstan-return ApiResponse
     */
    public function respond(Result $result): ApiResponse
    {
        return $result->match(
            onSuccess: fn (RoleSuccess $success) => new ApiResponse(
                data: new RoleCollection(resource: $success->result),
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
