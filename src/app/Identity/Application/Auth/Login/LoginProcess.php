<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login;

use App\Shared\Application\Process;
use App\Identity\Application\Auth\Login\Handler\ThrottleLoginsHandler;
use App\Identity\Application\Auth\Login\Handler\AuthenticateUserHandler;
use App\Identity\Application\Auth\Login\Handler\RevokeOldRefreshTokensHandler;
use App\Identity\Application\Auth\Login\Handler\IssueJwtTokensHandler;
use App\Identity\Application\Auth\Login\Handler\PersistRefreshTokenHandler;
use App\Shared\Application\Result\Result;
use App\Identity\Application\Auth\Login\Output\LoginSuccess;
use App\Identity\Application\Auth\Login\Output\LoginError;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * @phpstan-extends Process<LoginCommand, Result<LoginSuccess, LoginError>>
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
     * @phpstan-return Result<LoginSuccess, LoginError>
     */
    public function __invoke(LoginCommand $command): Result
    {
        try {
            $jobId = Str::uuid7()->toString();

            /** @phpstan-var array<string, mixed> $data */
            $data = $command->toArray();

            dispatch_sync(new LoginJob(
                jobId: $jobId,
                data: $data
            ));

            /** @phpstan-var array{access_token: string, refresh_token: string, ttl: int} $cached */
            $cached = Cache::get(key: "login:{$jobId}");
            $result = new LoginSuccess(result: $cached);
            
            return Result::success(value: $result);
        }

        catch (\Throwable $e) {
            Log::critical(message: $e::class, context: [
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]);

            return Result::failure(
                error: LoginError::UnexpectedError
            );
        }
    }
}
