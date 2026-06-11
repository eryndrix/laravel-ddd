<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Forgot;

use App\Shared\Application\Process;
use App\Identity\Application\Password\Forgot\Handler\ThrottleForgotPasswordsHandler;
use App\Identity\Application\Password\Forgot\Handler\ValidateEmailFormatHandler;
use App\Identity\Application\Password\Forgot\Handler\ValidateEmailExistsHandler;
use App\Identity\Application\Password\Forgot\Handler\SendResetLinkHandler;
use App\Shared\Application\Result\Result;
use App\Shared\Application\Handler\HandlerException;
use Illuminate\Support\Facades\Log;

/**
 * @phpstan-extends Process<ForgotPasswordCommand, ForgotPasswordCommand>
 */
final class ForgotPasswordProcess extends Process
{
    /**
     * @phpstan-var list<class-string>
     */
    protected array $handlers = [
        ThrottleForgotPasswordsHandler::class,
        ValidateEmailFormatHandler::class,
        ValidateEmailExistsHandler::class,
        SendResetLinkHandler::class
    ];

    /**
     * @phpstan-param ForgotPasswordCommand $command
     * @phpstan-return Result<string, ForgotPasswordError>
     */
    public function __invoke(ForgotPasswordCommand $command): Result
    {
        try {
            $this->run(payload: $command);

            return Result::success(
                value: 'password.forgot.success'
            );
        }

        catch (HandlerException $e) {
            /** @phpstan-var ForgotPasswordError $error */
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
                error: ForgotPasswordError::Unknown
            );
        }
    }
}
