<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Forgot;

use App\Shared\Application\Process;
use App\Identity\Application\Password\Forgot\Handler\ValidateEmailExistsHandler;
use App\Identity\Application\Password\Forgot\Handler\SendResetLinkHandler;

/**
 * @phpstan-extends Process<ForgotPasswordCommand, mixed>
 */
final class ForgotPasswordProcess extends Process
{
    /**
     * @phpstan-var list<class-string>
     */
    protected array $handlers = [
        ValidateEmailExistsHandler::class,
        SendResetLinkHandler::class
    ];

    /**
     * @phpstan-param ForgotPasswordCommand $command
     * @phpstan-return void
     */
    public function execute(ForgotPasswordCommand $command): void
    {
        $this->run(payload: $command);
    }
}
