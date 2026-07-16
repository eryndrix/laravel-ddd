<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Verify\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Email\Verify\Exception\InvalidVerificationHashException;
use App\Identity\Application\Email\Verify\VerifyEmailQuery;

final class ValidateHashHandler extends Handler
{
    /**
     * @phpstan-param VerifyEmailQuery $query
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws InvalidVerificationHashException
     */
    public function handle(
        VerifyEmailQuery $query, \Closure $next): mixed
    {
        /** @phpstan-var \App\Identity\Domain\User $user */
        $user = $query->user;
        $email = $user->email->value();

        if (sha1(string: $email) !== $query->hash) {
            throw new InvalidVerificationHashException();
        }

        return $next($query);
    }
}
