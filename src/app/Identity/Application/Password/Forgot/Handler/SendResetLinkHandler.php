<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Forgot\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Password\Forgot\ForgotPasswordCommand;
use App\Identity\Application\Password\Forgot\Exception\PasswordResetLinkNotSentException;
use Illuminate\Support\Facades\Password;

final class SendResetLinkHandler extends Handler
{
    /**
     * @phpstan-param ForgotPasswordCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws PasswordResetLinkNotSentException
     */
    public function handle(
        ForgotPasswordCommand $command, \Closure $next): mixed
    {
        $status = Password::sendResetLink(
            credentials: ['email' => $command->email]
        );

        if ($status !== Password::RESET_LINK_SENT) {
            throw new PasswordResetLinkNotSentException();
        }

        return $next($command);
    }
}
