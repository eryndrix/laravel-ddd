<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Logout;

use App\Shared\Application\Process;
use App\Identity\Application\Auth\Logout\Handler\RevokeRefreshTokensHandler;
use App\Identity\Application\Auth\Logout\Handler\InvalidateJwtHandler;

/**
 * @phpstan-extends Process<LogoutCommand, mixed>
 */
final class LogoutProcess extends Process
{
    /**
     * @phpstan-var list<class-string>
     */
    protected array $handlers = [
        RevokeRefreshTokensHandler::class,
        InvalidateJwtHandler::class
    ];

    /**
     * @phpstan-param LogoutCommand $command
     * @phpstan-return void
     */
    public function execute(LogoutCommand $command): void
    {
        $this->run(payload: $command);
    }
}
