<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Verify\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Email\Verify\Exception\EmailAlreadyVerifiedException;
use App\Identity\Application\Email\Verify\VerifyEmailQuery;

final class ValidateEmailNotVerifiedHandler extends Handler
{
    /**
     * @phpstan-param VerifyEmailQuery $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws EmailAlreadyVerifiedException
     */
    public function handle(
        VerifyEmailQuery $command, \Closure $next): mixed
    {
        /** @phpstan-var \App\Identity\Domain\User $user */
        $user = $command->user;
        
        if ($user->isEmailVerified()) {
            throw new EmailAlreadyVerifiedException();
        }

        return $next($command);
    }
}
