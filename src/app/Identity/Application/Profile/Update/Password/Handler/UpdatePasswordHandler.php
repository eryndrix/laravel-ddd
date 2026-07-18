<?php declare(strict_types=1);

namespace App\Identity\Application\Profile\Update\Password\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Profile\Update\Password\UpdatePasswordCommand;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Domain\Password\Password;

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
     * 
     * @throws \LogicException
     */
    public function handle(
        UpdatePasswordCommand $command, \Closure $next): mixed
    {
        $user = $command->user;

        $newPassword = Password::fromPlain(
            value: $command->password
        );

        $user->changePassword(
            newPassword: $newPassword
        );

        $this->repository->save(user: $user);

        return $next($command);
    }
}
