<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Update;

use App\Shared\Application\Process;
use App\Identity\Application\Email\Update\Handler\LoadAuthUserHandler;
use App\Identity\Application\Email\Update\Handler\ValidateEmailHandler;
use App\Identity\Application\Email\Update\Handler\PersistNewEmailHandler;
use App\Identity\Application\Email\Update\Handler\SendVerificationEmailHandler;
use App\Shared\Application\Result\Result;
use App\Shared\Application\Handler\HandlerException;
use Illuminate\Support\Facades\Log;

/**
 * @phpstan-extends Process<UpdateEmailCommand, mixed>
 */
final class UpdateEmailProcess extends Process
{
    /**
     * @phpstan-var list<class-string>
     */
    protected array $handlers = [
        LoadAuthUserHandler::class,
        ValidateEmailHandler::class,
        PersistNewEmailHandler::class,
        SendVerificationEmailHandler::class
    ];

    /**
     * @phpstan-param UpdateEmailCommand $command
     * @phpstan-return Result<string, UpdateEmailError>
     */
    public function __invoke(UpdateEmailCommand $command): Result
    {
        try {
            $this->run(payload: $command);

            return Result::success(
                value: 'identity.email.update.success'
            );
        }

        catch (HandlerException $e) {
            /** @phpstan-var UpdateEmailError $error */
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
                error: UpdateEmailError::Unknown
            );
        }
    }
}
