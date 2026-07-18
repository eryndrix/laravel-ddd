<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Update\Handler;

use App\Shared\Application\Handler;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Application\Email\Update\UpdateEmailCommand;
use App\Shared\Application\Exception\UserNotFoundException;
use App\Identity\Domain\Email\Email;

final class PersistNewEmailHandler extends Handler
{
    /**
     * @phpstan-param UserRepositoryInterface $repository
     */
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    /**
     * @phpstan-param UpdateEmailCommand $command
     * @phpstan-param \Closure(UpdateEmailCommand):mixed $next
     * 
     * @phpstan-return mixed
     * 
     * @throws UserNotFoundException
     */
    public function handle(
        UpdateEmailCommand $command, \Closure $next): mixed
    {
        /* @phpstan-var \App\Identity\Domain\User $user */
        $user = $command->user;

        if (is_null(value: $user)) {
            throw new UserNotFoundException();
        }

        $newEmail = Email::of(value: $command->email);
        $user->changeEmail(email: $newEmail);

        $this->repository->save(user: $user);

        return $next($command);
    }
}
