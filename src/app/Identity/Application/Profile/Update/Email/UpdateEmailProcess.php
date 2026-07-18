<?php declare(strict_types=1);

namespace App\Identity\Application\Profile\Update\Email;

use App\Shared\Application\Process;
use App\Identity\Application\Profile\Update\Email\Handler\ValidateEmailHandler;
use App\Identity\Application\Profile\Update\Email\Handler\PersistNewEmailHandler;
use App\Identity\Application\Profile\Update\Email\Handler\DispatchVerificationLinkHandler;

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
