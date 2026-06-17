<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Verify\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Email\Verify\VerifyEmailQuery;
use App\Identity\Application\Email\Verify\VerifyEmailError;
use App\Shared\Application\Handler\HandlerException;

final class ValidateHashHandler extends Handler
{
    /**
     * @phpstan-param VerifyEmailQuery $query
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws HandlerException<VerifyEmailError>
     */
    public function handle(
        VerifyEmailQuery $query, \Closure $next): mixed
    {
        /** @phpstan-var \App\Identity\Domain\User $user */
        $user = $query->user;

        if (sha1(string: $user->email->value())
            !== $query->hash
        ) {
            throw new HandlerException(
                error: VerifyEmailError::InvalidHash
            );
        }

        return $next($query);
    }
}
