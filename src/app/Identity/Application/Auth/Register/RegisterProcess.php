<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Register;

use App\Shared\Application\Process;
use App\Identity\Application\Auth\Register\Handler\RegisterUserHandler;
use App\Identity\Application\Auth\Register\Handler\AttachDefaultRoleHandler;
use App\Shared\Application\Result\Result;
use App\Identity\Application\Auth\Register\Output\RegisterSuccess;
use App\Identity\Application\Auth\Register\Output\RegisterError;
use Illuminate\Support\Facades\Log;

/**
 * @phpstan-extends Process<
 *     RegisterCommand,
 *     Result<RegisterSuccess, RegisterError>
 * >
 */
final class RegisterProcess extends Process
{
    /**
     * @phpstan-var list<class-string>
     */
    protected array $handlers = [
        AttachDefaultRoleHandler::class,
        RegisterUserHandler::class
    ];

    /**
     * @phpstan-param RegisterCommand $command
     * @phpstan-return Result<RegisterSuccess, RegisterError>
     */
    public function __invoke(RegisterCommand $command): Result
    {
        try {
            dispatch_sync(
                new RegisterJob(command: $command)
            );

            $result = new RegisterSuccess();
            return Result::success(value: $result);
        }

        catch (\Throwable $e) {
            Log::critical(message: $e::class, context: [
                'line' => $e->getLine(),
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]);

            return Result::failure(
                error: RegisterError::UnexpectedError
            );
        }
    }
}
