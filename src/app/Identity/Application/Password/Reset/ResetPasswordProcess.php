<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Reset;

use App\Shared\Application\Process;
use App\Identity\Application\Password\Reset\Handler\ValidateResetTokenHandler;
use App\Identity\Application\Password\Reset\Handler\ApplyPasswordResetHandler;

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
        ApplyPasswordResetHandler::class
    ];

    /**
     * @phpstan-param ResetPasswordCommand $command
     * @phpstan-return void
     */
    public function execute(ResetPasswordCommand $command): void
    {
        $this->run(payload: $command);
    }
}
