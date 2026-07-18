<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Update;

use App\Shared\Application\Process;
use App\Identity\Application\Password\Update\Handler\LoadAuthUserHandler;
use App\Identity\Application\Password\Update\Handler\ValidatePasswordHandler;
use App\Identity\Application\Password\Update\Handler\UpdatePasswordHandler;
use App\Shared\Application\Result\Result;
use App\Shared\Application\Handler\HandlerException;
use Illuminate\Support\Facades\Log;

/**
 * @phpstan-extends Process<UpdatePasswordCommand, mixed>
 */
final class UpdatePasswordProcess extends Process
{
    /**
     * @phpstan-var list<class-string>
     */
    protected array $handlers = [
        LoadAuthUserHandler::class,
        ValidatePasswordHandler::class,
        UpdatePasswordHandler::class,
    ];

    /**
     * @phpstan-param UpdatePasswordCommand $command
     * @phpstan-return Result<string, UpdatePasswordError>
     */
    public function __invoke(UpdatePasswordCommand $command): Result
    {
        try {
            $this->run(payload: $command);

            return Result::success(
                value: 'identity.password.update.success'
            );
        }

        catch (HandlerException $e) {
            /** @phpstan-var UpdatePasswordError $error */
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
                error: UpdatePasswordError::Unknown
            );
        }
    }
}
