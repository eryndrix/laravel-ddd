<?php declare(strict_types=1);

namespace App\Identity\Application\Profile\Update\Password\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Profile\Update\Password\UpdatePasswordCommand;

final class ValidatePasswordHandler extends Handler
{
    /**
     * @phpstan-param UpdatePasswordCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws \LogicException
     */
    public function handle(
        UpdatePasswordCommand $command, \Closure $next): mixed
    {
        if ($command->password !== $command->passwordConfirmation) {
            throw new \LogicException();
        }

        return $next($command);
    }
}
