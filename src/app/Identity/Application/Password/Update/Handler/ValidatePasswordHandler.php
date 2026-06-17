<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Update\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Password\Update\UpdatePasswordCommand;
use App\Identity\Application\Password\Update\UpdatePasswordError;
use App\Shared\Application\Handler\HandlerException;

final class ValidatePasswordHandler extends Handler
{
    /**
     * @phpstan-param UpdatePasswordCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws HandlerException<UpdatePasswordError>
     */
    public function handle(
        UpdatePasswordCommand $command, \Closure $next): mixed
    {
        if ($command->password !== $command->passwordConfirmation) {
            throw new HandlerException(
                error: UpdatePasswordError::Mismatch
            );
        }

        return $next($command);
    }
}
