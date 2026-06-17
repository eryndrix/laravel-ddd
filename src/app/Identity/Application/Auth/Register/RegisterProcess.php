<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Register;

use App\Shared\Application\Process;
use App\Identity\Application\Auth\Register\Handler\AttachDefaultRoleHandler;
use App\Identity\Application\Auth\Register\Handler\RegisterUserHandler;
use App\Shared\Application\Result\Result;
use App\Shared\Application\Handler\HandlerException;
use Illuminate\Support\Facades\Log;

/**
 * @phpstan-extends Process<RegisterCommand, mixed>
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
     * @phpstan-return Result<string, RegisterError>
     */
    public function __invoke(RegisterCommand $command): Result
    {
        try {
            dispatch_sync(
                new RegisterJob(command: $command)
            );

            return Result::success(
                value: 'identity.register.success'
            );
        }

        catch (HandlerException $e) {
            /** @phpstan-var RegisterError $error */
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
                error: RegisterError::Unknown
            );
        }
    }
}
