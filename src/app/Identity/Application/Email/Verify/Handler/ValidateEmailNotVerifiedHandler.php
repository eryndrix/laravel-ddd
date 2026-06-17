<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Verify\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Email\Verify\VerifyEmailQuery;
use App\Identity\Application\Email\Verify\VerifyEmailError;
use App\Shared\Application\Handler\HandlerException;

final class ValidateEmailNotVerifiedHandler extends Handler
{
    /**
     * @phpstan-param VerifyEmailQuery $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws HandlerException<VerifyEmailError>
     */
    public function handle(
        VerifyEmailQuery $command, \Closure $next): mixed
    {
        /** @phpstan-var \App\Identity\Domain\User $user */
        $user = $command->user;
        
        if ($user->isEmailVerified()) {
            throw new HandlerException(
                error: VerifyEmailError::EmailVerified
            );
        }

        return $next($command);
    }
}
