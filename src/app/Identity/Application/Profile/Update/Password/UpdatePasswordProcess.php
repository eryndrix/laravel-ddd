<?php declare(strict_types=1);

namespace App\Identity\Application\Profile\Update\Password;

use App\Shared\Application\Process;
use App\Identity\Application\Profile\Update\Password\Handler\LoadAuthUserHandler;
use App\Identity\Application\Profile\Update\Password\Handler\ValidatePasswordHandler;
use App\Identity\Application\Profile\Update\Password\Handler\UpdatePasswordHandler;

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
     * @phpstan-param UpdateEmailCommand $command
     * @phpstan-return void
     */
    public function execute(UpdatePasswordCommand $command): void
    {
        $this->run(payload: $command);
    }
}
