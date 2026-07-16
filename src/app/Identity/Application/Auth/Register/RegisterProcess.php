<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Register;

use App\Shared\Application\Process;
use App\Identity\Application\Auth\Register\Handler\AttachDefaultRoleHandler;
use App\Identity\Application\Auth\Register\Handler\PersistRegisteredUserHandler;
use App\Identity\Application\Auth\Register\Handler\DispatchRegisteredEventsHandler;
use App\Shared\Application\Result\Result;

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
        PersistRegisteredUserHandler::class,
        DispatchRegisteredEventsHandler::class
    ];

    /**
     * @phpstan-param RegisterCommand $command
     * @phpstan-return void
     */
    public function execute(RegisterCommand $command): void
    {
        $this->run(payload: $command);
    }
}
