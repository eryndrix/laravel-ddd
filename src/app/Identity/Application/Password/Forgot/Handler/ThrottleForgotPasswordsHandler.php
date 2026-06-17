<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Forgot\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Password\Forgot\ForgotPasswordCommand;
use Illuminate\Support\Facades\RateLimiter;
use App\Identity\Application\Password\Forgot\ForgotPasswordError;
use App\Shared\Application\Handler\HandlerException;

final class ThrottleForgotPasswordsHandler extends Handler
{
    /**
     * @phpstan-param ForgotPasswordCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws HandlerException<ForgotPasswordError>
     */
    public function handle(
        ForgotPasswordCommand $command, \Closure $next): mixed
    {
        $key = 'forgot_password:' . $command->email;

        if (RateLimiter::tooManyAttempts(
            key: $key,
            maxAttempts: 3
        )) {
            throw new HandlerException(
                error: ForgotPasswordError::TooManyAttempts
            );
        }

        RateLimiter::hit(key: $key);

        return $next($command);
    }
}
