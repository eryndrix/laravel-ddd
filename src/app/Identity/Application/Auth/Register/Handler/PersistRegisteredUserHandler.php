<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Register\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Auth\Register\RegisterCommand;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Application\Auth\Register\Exception\RoleIdRequiredException;
use App\Identity\Domain\Register\UserRegistration;
use App\Shared\Application\Exception\PipelineFailedException;
use App\Identity\Domain\{Email\Email, Password\Password};

final class PersistRegisteredUserHandler extends Handler
{
    /**
     * @phpstan-param UserRepositoryInterface $repository
     */
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    /**
     * @phpstan-param RegisterCommand $command
     * @phpstan-param \Closure(RegisterCommand):mixed $next
     * 
     * @phpstan-return mixed
     * 
     * @throws RoleIdRequiredException
     */
    public function handle(
        RegisterCommand $command, \Closure $next): mixed
    {
        if (is_null(value: $command->roleId)) {
            throw new RoleIdRequiredException();
        }
        
        $user = UserRegistration::new(
            firstName: $command->firstName,
            lastName: $command->lastName,
            email: Email::of(value: $command->email),
            password: Password::fromPlain(
                value: $command->password
            ),
            roleId: $command->roleId
        );

        $this->repository->save(user: $user);

        $command->user = $user;

        return $next($command);
    }
}
