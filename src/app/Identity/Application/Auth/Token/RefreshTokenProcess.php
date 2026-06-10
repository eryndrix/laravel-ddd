<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token;

use App\Shared\Application\Process;
use App\Identity\Application\Auth\Token\Handler\ResolveRefreshTokenHandler;
use App\Identity\Application\Auth\Token\Handler\CheckTokenExpirationHandler;
use App\Identity\Application\Auth\Token\Handler\CheckTokenAbilitiesHandler;
use App\Identity\Application\Auth\Token\Handler\DetectTokenReuseHandler;
use App\Identity\Application\Auth\Token\Handler\LoadUserHandler;
use App\Identity\Application\Auth\Token\Handler\RevokeOldTokenHandler;
use App\Identity\Application\Auth\Token\Handler\EmitNewTokensHandler;
use App\Identity\Application\Auth\Token\Handler\PersistNewRefreshTokenHandler;
use App\Shared\Application\Result\Result;
use App\Shared\Application\Handler\HandlerException;
use Illuminate\Support\Facades\Log;

/**
 * @phpstan-extends Process<RefreshTokenCommand, RefreshTokenCommand>
 */
final class RefreshTokenProcess extends Process
{
    /**
     * @phpstan-var list<class-string>
     */
    protected array $handlers = [
        ResolveRefreshTokenHandler::class,
        CheckTokenExpirationHandler::class,
        CheckTokenAbilitiesHandler::class,
        DetectTokenReuseHandler::class,
        LoadUserHandler::class,
        RevokeOldTokenHandler::class,
        EmitNewTokensHandler::class,
        PersistNewRefreshTokenHandler::class,
    ];

    /**
     * @phpstan-param RefreshTokenCommand $command
     * @phpstan-return Result<array<string, mixed>, RefreshTokenError>
     */
    public function __invoke(
        RefreshTokenCommand $command): Result
    {
        try {
            $result = $this->run(payload: $command);

            /** @phpstan-var array<string, mixed> $token */
            $token = $result->jwtTokenPair;

            return Result::success(value: $token);
        }

        catch (HandlerException $e) {
            /** @phpstan-var RefreshTokenError $error */
            $error = $e->getError();
            return Result::failure(error: $error);
        }

        catch (\Throwable $e) {
            Log::critical(message: $e::class, context: [
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]);

            return Result::failure(
                error: RefreshTokenError::Unknown
            );
        }
    }
}
