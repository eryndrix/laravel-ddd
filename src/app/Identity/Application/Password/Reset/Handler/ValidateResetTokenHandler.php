<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Reset\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Password\Reset\ResetPasswordCommand;
use Illuminate\Support\Facades\Password;
use App\Identity\Application\Password\Reset\ResetPasswordError;
use App\Shared\Application\Handler\HandlerException;

final class ValidateResetTokenHandler extends Handler
{
    /**
     * @phpstan-param ResetPasswordCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws HandlerException<ResetPasswordError>
     * @throws \LogicException
     */
    public function handle(
        ResetPasswordCommand $command, \Closure $next): mixed
    {
        $user = Password::getUser(
            credentials: ['email' => $command->email]
        );

        if (is_null(value: $user)) {
            throw new HandlerException(
                error: ResetPasswordError::InvalidEmail
            );
        }

        $status = Password::tokenExists(
            user: $user,
            token: $command->token
        );

        if (!$status) {
            throw new \LogicException(
                message: 'Invalid or expired reset token.'
            );
        }

        return $next($command);
    }
}
