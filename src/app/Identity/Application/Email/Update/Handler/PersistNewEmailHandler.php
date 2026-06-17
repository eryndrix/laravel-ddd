<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Update\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Email\Update\UpdateEmailCommand;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Domain\Changing\UserChanger;
use App\Shared\Domain\Email\Email;

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
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     */
    public function handle(
        UpdateEmailCommand $command, \Closure $next): mixed
    {
        $authUser = $command->user;
        /** @phpstan-var \App\Identity\Infrastructure\Auth\UserAdapter $authUser */
        $profile = $authUser->unwrap();

        $newEmail = Email::of(value: $command->email);
        $user = new UserChanger(user: $profile)
            ->beginChange()
            ->email(email: $newEmail)
            ->endChange();

        $this->repository->save(user: $user);

        return $next($command);
    }
}
