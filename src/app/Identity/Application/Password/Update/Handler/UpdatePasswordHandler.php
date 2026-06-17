<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Update\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Password\Update\UpdatePasswordCommand;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Domain\Password\Password;
use App\Identity\Domain\Access\Auth\UserAdapterInterface;
use App\Identity\Domain\Changing\UserChanger;

final class UpdatePasswordHandler extends Handler
{
    /**
     * @phpstan-param UserRepositoryInterface $repository
     */
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    /**
     * @phpstan-param UpdatePasswordCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     */
    public function handle(
        UpdatePasswordCommand $command, \Closure $next): mixed
    {
        $authUser = $command->user;
        
        /** @phpstan-var UserAdapterInterface $authUser */
        $profile = $authUser->unwrap();

        $newPassword = Password::fromPlain(
            value: $command->password
        );

        $user = new UserChanger(user: $profile)
            ->beginChange()
            ->password(newPassword: $newPassword)
            ->endChange();

        $this->repository->save(user: $user);

        return $next($command);
    }
}
