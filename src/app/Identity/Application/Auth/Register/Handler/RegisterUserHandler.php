<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Register\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Auth\Register\RegisterCommand;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Domain\Password\Password;
use App\Shared\Domain\Email\Email;
use App\Identity\Domain\Creating\UserCreator;
use App\Identity\Application\Auth\Register\RegisterError;
use App\Shared\Application\Result\Result;

final class RegisterUserHandler extends Handler
{
    /**
     * @phpstan-param UserRepositoryInterface $repository
     */
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    /**
     * @phpstan-param RegisterCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws \RuntimeException
     */
    public function handle(
        RegisterCommand $command, \Closure $next): mixed
    {
        if (is_null(value: $command->roleId)) {
            throw new \RuntimeException(
                message: 'Role ID is required.'
            );
        }

        try {
            $email = Email::of(value: $command->email);
            $password = Password::fromPlain(
                value: $command->password
            );
        }

        catch (\DomainException $e) {
            return Result::failure(
                error: RegisterError::InvalidCredentials
            );
        }

        $user = UserCreator::new(
            name: $command->name,
            email: $email,
            password: $password,
            roleId: $command->roleId
        );

        $this->repository->save(user: $user);
        
        return $next($command);
    }
}
