<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Auth\Login\LoginCommand;
use Illuminate\Support\Facades\RateLimiter;
use App\Identity\Application\Auth\Login\LoginError;
use App\Shared\Application\Result\Result;

final class ThrottleLoginsHandler extends Handler
{
    /**
     * @phpstan-param LoginCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws \RuntimeException
     */
    public function handle(
        LoginCommand $command, \Closure $next): mixed
    {
        $key = 'login:' . $command->email;

        if (RateLimiter::tooManyAttempts(
            key: $key,
            maxAttempts: 5
        )) {
            RateLimiter::availableIn(key: $key);
            
            return Result::failure(
                error: LoginError::TooManyAttempts
            );
        }

        RateLimiter::hit(key: $key);

        return $next($command);
    }
}
