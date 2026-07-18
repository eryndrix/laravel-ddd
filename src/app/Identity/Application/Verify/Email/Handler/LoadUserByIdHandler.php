<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Verify\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Email\Verify\VerifyEmailQuery;
use App\Shared\Application\Exception\UserNotFoundException;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\Id\UserId;

final class LoadUserByIdHandler extends Handler
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
     * 
     * @throws UserNotFoundException
     */
    public function handle(
        VerifyEmailQuery $query, \Closure $next): mixed
    {
        $userId = UserId::of(value: $query->userId);
        $user = $this->repository->findById(id: $userId);
        
        if (is_null(value: $user)) {
            throw new UserNotFoundException();
        }

        $query = $query->withUser(user: $user);

        return $next($query);
    }
}
