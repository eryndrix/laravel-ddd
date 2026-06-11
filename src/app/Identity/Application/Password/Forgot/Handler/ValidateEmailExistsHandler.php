<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Forgot\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Password\Forgot\ForgotPasswordCommand;
use App\Identity\Domain\Access\Auth\UserProviderInterface;

final class ValidateEmailExistsHandler extends Handler
{
    /**
     * @phpstan-param UserProviderInterface $userProvider
     */
    public function __construct(
        private UserProviderInterface $userProvider
    ) {}

    /**
     * @phpstan-param ForgotPasswordCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     */
    public function handle(
        ForgotPasswordCommand $command, \Closure $next): mixed
    {
        if (!isset($command->email)) {
            $command->emailExists = false;
            return $next($command);
        }

        $credentials = ['email' => $command->email];

        $user = $this->userProvider->retrieveByCredentials(
            credentials: $credentials
        );

        if (is_null(value: $user)) {
            $command->emailExists = false;
            return $next($command);
        }

        $command->emailExists = true;

        return $next($command);
    }
}
