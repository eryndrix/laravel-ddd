<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Forgot\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Password\Forgot\ForgotPasswordCommand;
use Illuminate\Support\Facades\{Password, Log};

final class SendResetLinkHandler extends Handler
{
    /**
     * @phpstan-param ForgotPasswordCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws \LogicException
     */
    public function handle(
        ForgotPasswordCommand $command, \Closure $next): mixed
    {
        $status = Password::sendResetLink(
            credentials: ['email' => $command->email]
        );

        if ($status !== Password::RESET_LINK_SENT) {
            throw new \LogicException(
                message: 'Password reset link not sent.'
            );
        }

        return $next($command);
    }
}
