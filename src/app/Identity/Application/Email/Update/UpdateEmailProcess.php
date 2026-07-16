<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Update;

use App\Shared\Application\Process;
use App\Identity\Application\Email\Update\Handler\ValidateEmailHandler;
use App\Identity\Application\Email\Update\Handler\PersistNewEmailHandler;
use App\Identity\Application\Email\Update\Handler\DispatchVerificationLinkHandler;

/**
 * @phpstan-extends Process<UpdateEmailCommand, mixed>
 */
final class UpdateEmailProcess extends Process
{
    /**
     * @phpstan-var list<class-string>
     */
    protected array $handlers = [
        ValidateEmailHandler::class,
        PersistNewEmailHandler::class,
        DispatchVerificationLinkHandler::class
    ];

    /**
     * @phpstan-param UpdateEmailCommand $command
     * @phpstan-return void
     */
    public function execute(UpdateEmailCommand $command): void
    {
        $this->run(payload: $command);
    }
}
