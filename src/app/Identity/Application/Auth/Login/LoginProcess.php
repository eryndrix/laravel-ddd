<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login;

use App\Shared\Application\Process;
use App\Identity\Application\Auth\Login\Handler\ThrottleLoginsHandler;
use App\Identity\Application\Auth\Login\Handler\AuthenticateUserHandler;
use App\Identity\Application\Auth\Login\Handler\RevokeOldRefreshTokensHandler;
use App\Identity\Application\Auth\Login\Handler\IssueJwtTokensHandler;
use App\Identity\Application\Auth\Login\Handler\PersistRefreshTokenHandler;
use App\Shared\Application\Result\Result;
use App\Shared\Application\Handler\HandlerException;
use Illuminate\Support\Facades\Log;

/**
 * @phpstan-extends Process<LoginCommand, LoginCommand>
 */
final class LoginProcess extends Process
{
    /**
     * @phpstan-var list<class-string>
     */
    protected array $handlers = [
        ThrottleLoginsHandler::class,
        AuthenticateUserHandler::class,
        RevokeOldRefreshTokensHandler::class,
        IssueJwtTokensHandler::class,
        PersistRefreshTokenHandler::class
    ];

    /**
     * @phpstan-param LoginCommand $command
     * @phpstan-return Result<array<string, mixed>, LoginError>
     */
    public function __invoke(LoginCommand $command): Result
    {
        try {
            $result = $this->run(payload: $command);

            /** @phpstan-var array<string, mixed> $jwtTokenPair */
            $jwtTokenPair = $result->jwtTokenPair;

            return Result::success(value: $jwtTokenPair);
        }

        catch (HandlerException $e) {
            /** @phpstan-var LoginError $error */
            $error = $e->getError();
            return Result::failure(error: $error);
        }

        catch (\Throwable $e) {
            Log::critical(message: $e::class, context: [
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]);

            return Result::failure(error: LoginError::Unknown);
        }
    }
}
