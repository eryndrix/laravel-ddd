<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Reset\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Password\Reset\ResetPasswordCommand;
use App\Identity\Application\Password\Reset\Exception\InvalidResetEmailException;
use App\Identity\Application\Password\Reset\Exception\InvalidResetTokenException;
use App\Identity\Domain\Email\Email;
use Illuminate\Support\Facades\Password;

final class ValidateResetTokenHandler extends Handler
{
    /**
     * @phpstan-param ResetPasswordCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws InvalidResetEmailException
     * @throws InvalidResetTokenException
     */
    public function handle(
        ResetPasswordCommand $command, \Closure $next): mixed
    {
        $email = Email::of(value: $command->email);
        $credentials = ['email' => $email->value()];

        $user = Password::getUser(credentials: $credentials);

        if (is_null(value: $user)) {
            throw new InvalidResetEmailException();
        }

        $status = Password::tokenExists(
            user: $user,
            token: $command->token
        );

        if (!$status) {
            throw new InvalidResetTokenException();
        }

        return $next($command);
    }
}
