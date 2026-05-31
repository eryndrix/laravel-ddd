<?php declare(strict_types=1);

namespace App\Privilege\Presentation\Responder;

use App\Shared\Presentation\Responder;
use App\Privilege\Presentation\RoleCollection;
use Eryndrix\Paginator\Paginator;
use App\Shared\Application\Result\Result;
use App\Shared\Presentation\Response\ApiResponse;
use Illuminate\Http\Response;

/**
 * @phpstan-extends Responder<
 *     Paginator<\App\Privilege\Domain\Role>,
 *     string
 * >
 */
final class ListRoleResponder extends Responder
{
    /**
     * @phpstan-param Result<
     *     Paginator<\App\Privilege\Domain\Role>,
     *     string
     * > $result
     * 
     * @phpstan-return ApiResponse
     */
    public function respond(Result $result): ApiResponse
    {
        return $result->match(
            onSuccess: fn (Paginator $roles) => new ApiResponse(
                data: new RoleCollection(resource: $roles),
                status: Response::HTTP_OK
            ),
            onError: fn (string $error) => new ApiResponse(
                data: ['message' => $error],
                status: Response::HTTP_BAD_REQUEST
            ),
        );
    }
}
