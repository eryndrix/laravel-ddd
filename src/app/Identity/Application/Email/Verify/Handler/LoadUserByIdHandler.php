<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Verify\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Email\Verify\VerifyEmailQuery;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Shared\Application\Handler\HandlerException;
use App\Identity\Application\Email\Verify\VerifyEmailError;
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
     * @throws HandlerException<VerifyEmailError>
     */
    public function handle(
        VerifyEmailQuery $query, \Closure $next): mixed
    {
        try {
            $userId = UserId::of(value: $query->userId);
        }

        catch (\DomainException $e) {
            throw new HandlerException(
                error: VerifyEmailError::Failed
            );
        }

        $user = $this->repository->findById(id: $userId);
        
        if (is_null(value: $user)) {
            throw new HandlerException(
                error: VerifyEmailError::Failed
            );
        }

        $query = $query->withUser(user: $user);

        return $next($query);
    }
}
