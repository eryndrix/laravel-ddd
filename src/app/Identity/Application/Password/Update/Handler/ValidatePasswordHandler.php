<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Update\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Password\Update\UpdatePasswordCommand;
use App\Shared\Application\Handler\HandlerException;
use App\Identity\Application\Password\Update\UpdatePasswordError;
use App\Identity\Domain\Password\Password;

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
        try {
            $password = Password::fromPlain(value: $command->password);
            $passwordConfirmation = Password::fromPlain(
                value: $command->passwordConfirmation
            );

            if (!$password->equals(other: $passwordConfirmation)) {
                throw new HandlerException(
                    error: UpdatePasswordError::Mismatch
                );
            }
        }

        catch (\DomainException $e) {
            throw new HandlerException(
                error: UpdatePasswordError::InvalidPwdFormat
            );
        }

        return $next($command);
    }
}
