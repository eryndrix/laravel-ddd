<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Reset\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Password\Reset\ResetPasswordCommand;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Application\Password\Reset\Exception\PasswordResetFailedException;
use App\Identity\Domain\Access\Auth\UserAdapterInterface;
use Illuminate\Support\Facades\Password as PasswordEvent;
use App\Identity\Domain\Password\Password;

final class ApplyPasswordResetHandler extends Handler
{
    /**
     * @phpstan-param UserRepositoryInterface $repository
     */
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    /**
     * @phpstan-param ResetPasswordCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws PasswordResetFailedException
     */
    public function handle(
        ResetPasswordCommand $command, \Closure $next): mixed
    {
        $status = PasswordEvent::reset(
            credentials: [
                'email' => $command->email,
                'password' => $command->password,
                'password_confirmation' => $command->passwordConfirmation,
                'token' => $command->token
            ],
            callback: function (
                UserAdapterInterface $user, string $password): void {
                $user = $user->unwrap();
                $user->changePassword(
                    password: Password::fromPlain(
                        value: $password
                    )
                );

                $this->repository->save(user: $user);
            }
        );

        if ($status !== PasswordEvent::PASSWORD_RESET) {
            throw new PasswordResetFailedException();
        }

        return $next($command);
    }
}
