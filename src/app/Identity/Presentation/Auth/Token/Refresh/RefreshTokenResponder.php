<?php declare(strict_types=1);

namespace App\Identity\Presentation\Auth\Token\Refresh;

use App\Shared\Presentation\Responder;
use App\Shared\Presentation\Response\ApiResponse;
use App\Shared\Application\Exception\UserNotFoundException;
use App\Identity\Application\Auth\Token\Refresh\Exception\TokenNotFoundException;
use App\Identity\Application\Auth\Token\Refresh\Exception\TokenExpiredException;
use App\Identity\Application\Auth\Token\Refresh\Exception\MissingAbilityException;
use App\Identity\Application\Auth\Token\Refresh\Exception\TokenReuseDetectedException;
use App\Identity\Application\Auth\Token\Refresh\Exception\TokenIssuanceException;
use App\Identity\Application\Auth\Token\TokenData;
use App\Shared\Application\Result\Result;
use Illuminate\Http\Response;

/**
 * @phpstan-extends Responder<TokenData, \Throwable>
 */
final class RefreshTokenResponder extends Responder
{
    /**
     * @phpstan-param Result<TokenData, \Throwable> $result
     * @phpstan-return ApiResponse
     */
    public function respond(Result $result): ApiResponse
    {
        return $result->match(
            onSuccess: fn (TokenData $token) => new ApiResponse(
                data: $token->toArray(),
                status: Response::HTTP_OK
            ),
            onError: fn (\Throwable $e) => match (true) {
                $e instanceof \DomainException,
                $e instanceof MissingAbilityException => new ApiResponse(
                    data: ['message' => $e->getMessage()],
                    status: Response::HTTP_BAD_REQUEST
                ),
                $e instanceof TokenExpiredException,
                $e instanceof TokenNotFoundException,
                $e instanceof UserNotFoundException => new ApiResponse(
                    data: ['message' => $e->getMessage()],
                    status: Response::HTTP_UNAUTHORIZED
                ),
                $e instanceof TokenReuseDetectedException => new ApiResponse(
                    data: ['message' => $e->getMessage()],
                    status: Response::HTTP_FORBIDDEN
                ),
                $e instanceof TokenIssuanceException => new ApiResponse(
                    data: ['message' => $e->getMessage()],
                    status: Response::HTTP_INTERNAL_SERVER_ERROR
                ),
                default => new ApiResponse(
                    data: ['message' => __('identity.refresh_token.unknown')],
                    status: Response::HTTP_INTERNAL_SERVER_ERROR
                ),
            }
        );
    }
}
