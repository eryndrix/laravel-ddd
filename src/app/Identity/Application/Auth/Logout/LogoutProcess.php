<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Logout;

use App\Shared\Application\Process;
use App\Identity\Application\Auth\Logout\Handler\ValidateUserHandler;
use App\Identity\Application\Auth\Logout\Handler\RevokeRefreshTokensHandler;
use App\Identity\Application\Auth\Logout\Handler\InvalidateJwtHandler;
use App\Shared\Application\Result\Result;
use Illuminate\Support\Facades\Log;

/**
 * @phpstan-extends Process<LogoutCommand, Result<string, string>>
 */
final class LogoutProcess extends Process
{
    /**
     * @phpstan-var string
     */
    private const string SUCCESS = 'auth.logout.success';

    /**
     * @phpstan-var string
     */
    private const string FAILED = 'auth.logout.failed';

    /**
     * @phpstan-var list<class-string>
     */
    protected array $handlers = [
        ValidateUserHandler::class,
        RevokeRefreshTokensHandler::class,
        InvalidateJwtHandler::class
    ];

    /**
     * @phpstan-param LogoutCommand $command
     * @phpstan-return Result<string, string>
     */
    public function __invoke(LogoutCommand $command): Result
    {
        try {
            $result = $this->run(payload: $command);
            return Result::success(value: self::SUCCESS);
        }

        catch (\Throwable $e) {
            Log::critical(message: $e::class, context: [
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]);

            return Result::failure(error: self::FAILED);
        }
    }
}
