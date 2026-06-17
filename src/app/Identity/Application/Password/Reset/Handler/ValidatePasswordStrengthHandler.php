<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Reset\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Password\Reset\ResetPasswordCommand;
use App\Identity\Domain\Password\Password;
use App\Identity\Application\Password\Reset\ResetPasswordError;
use App\Shared\Application\Handler\HandlerException;

final class ValidatePasswordStrengthHandler extends Handler
{
    /**
     * @phpstan-param ResetPasswordCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws HandlerException<ResetPasswordError>
     */
    public function handle(
        ResetPasswordCommand $command, \Closure $next): mixed
    {
        try {
            Password::fromPlain(value: $command->password);
        }

        catch (\DomainException $e) {
            throw new HandlerException(
                error: ResetPasswordError::InvalidPwdFormat
            );
        }

        return $next($command);
    }
}
