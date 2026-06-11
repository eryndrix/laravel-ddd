<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Reset;

use App\Shared\Application\Process;
use App\Identity\Application\Password\Reset\Handler\ValidateResetTokenHandler;
use App\Identity\Application\Password\Reset\Handler\ValidatePasswordStrengthHandler;
use App\Identity\Application\Password\Reset\Handler\ApplyPasswordResetHandler;
use App\Shared\Application\Result\Result;
use App\Shared\Application\Handler\HandlerException;
use Illuminate\Support\Facades\Log;

/**
 * @phpstan-extends Process<ResetPasswordCommand, mixed>
 */
final class ResetPasswordProcess extends Process
{
    /**
     * @phpstan-var list<class-string>
     */
    protected array $handlers = [
        ValidateResetTokenHandler::class,
        ValidatePasswordStrengthHandler::class,
        ApplyPasswordResetHandler::class
    ];

    /**
     * @phpstan-param ResetPasswordCommand $command
     * @phpstan-return Result<string, ResetPasswordError>
     */
    public function __invoke(ResetPasswordCommand $command): Result
    {
        try {
            $this->run(payload: $command);

            return Result::success(
                value: 'password.reset.success'
            );
        }

        catch (HandlerException $e) {
            /** @phpstan-var ResetPasswordError $error */
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
                error: ResetPasswordError::Unknown
            );
        }
    }
}
