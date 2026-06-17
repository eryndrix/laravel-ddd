<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Verify\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Email\Verify\VerifyEmailQuery;
use App\Identity\Domain\Repository\UserRepositoryInterface;

final class MarkEmailAsVerifiedHandler extends Handler
{
    /**
     * @phpstan-param UserRepositoryInterface $repository
     */
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    /**
     * @phpstan-param VerifyEmailQuery $query
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     */
    public function handle(
        VerifyEmailQuery $query, \Closure $next): mixed
    {
        /** @phpstan-var \App\Identity\Domain\User $user */
        $user = $query->user;
        $user->markEmailAsVerified();

        $this->repository->save(user: $user);

        return $next($query);
    }
}
