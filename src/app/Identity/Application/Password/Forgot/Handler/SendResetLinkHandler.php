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
     */
    public function handle(
        ForgotPasswordCommand $command, \Closure $next): mixed
    {
        if (!isset($command->emailExists) || !$command->emailExists) {
            return $next($command);
        }

        $status = Password::sendResetLink(
            credentials: ['email' => $command->email]
        );

        if ($status !== Password::RESET_LINK_SENT) {
            Log::warning(
                message: 'Password reset link not sent',
                context: [
                    'email' => $command->email,
                    'status' => $status
                ]
            );
        }

        return $next($command);
    }
}
