<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login\Handler;

use App\Shared\Application\Handler;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Application\Auth\Login\LoginCommand;
use App\Shared\Application\Exception\UserNotFoundException;
use App\Identity\Domain\Access\Auth\UserAdapterInterface;

final class UpdateLastLoginAtHandler extends Handler
{
    /**
     * @phpstan-param UserRepositoryInterface $repository
     */
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}
    
    /**
     * @phpstan-param LoginCommand $command
     * @phpstan-param \Closure(LoginCommand):mixed $next
     * 
     * @phpstan-return mixed
     *
     * @throws UserNotFoundException
     */
    public function handle(
        LoginCommand $command, \Closure $next): mixed
    {
        /** @phpstan-var UserAdapterInterface $auth */
        $auth = $command->user;
        
        /** @phpstan-var \App\Identity\Domain\User $user */
        $user = $auth->unwrap();

        if (is_null(value: $user)) {
            throw new UserNotFoundException();
        }

        $user->markAsLoggedIn();

        $this->repository->save(user: $user);

        return $next($command);
    }
}
