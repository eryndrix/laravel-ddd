<?php declare(strict_types=1);

namespace App\Identity\Presentation\Auth\Login;

use App\Shared\Presentation\Responder;
use App\Shared\Presentation\Response\ApiResponse;
use App\Identity\Application\Auth\Login\Exception\InvalidCredentialsException;
use App\Shared\Application\Exception\UserNotFoundException;
use App\Identity\Application\Auth\Login\Exception\JwtTokenIssuanceException;
use App\Identity\Application\Auth\Token\TokenData;
use App\Shared\Application\Result\Result;
use Illuminate\Http\Response;

/**
 * @phpstan-extends Responder<TokenData, \Throwable>
 */
final class LoginResponder extends Responder
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
                $e instanceof \DomainException => new ApiResponse(
                    data: ['message' => $e->getMessage()],
                    status: Response::HTTP_BAD_REQUEST
                ),
                $e instanceof InvalidCredentialsException => new ApiResponse(
                    data: ['message' => $e->getMessage()],
                    status: Response::HTTP_UNAUTHORIZED
                ),
                $e instanceof UserNotFoundException,
                $e instanceof JwtTokenIssuanceException => new ApiResponse(
                    data: ['message' => __(key: 'identity.login.failed')],
                    status: Response::HTTP_INTERNAL_SERVER_ERROR
                ),
                default => new ApiResponse(
                    data: ['message' => __(key: 'identity.login.unknown')],
                    status: Response::HTTP_INTERNAL_SERVER_ERROR
                ),
            }
        );
    }
}
