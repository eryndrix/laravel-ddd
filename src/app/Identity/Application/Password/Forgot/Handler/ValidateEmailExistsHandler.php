<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Forgot\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Password\Forgot\ForgotPasswordCommand;
use App\Identity\Domain\Access\Auth\UserProviderInterface;
use App\Identity\Application\Password\Forgot\ForgotPasswordError;
use App\Shared\Application\Handler\HandlerException;

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
     * 
     * @throws HandlerException<ForgotPasswordError>
     */
    public function handle(
        ForgotPasswordCommand $command, \Closure $next): mixed
    {
        $credentials = ['email' => $command->email];

        $user = $this->userProvider->retrieveByCredentials(
            credentials: $credentials
        );

        if (is_null(value: $user)) {
            throw new HandlerException(
                error: ForgotPasswordError::EmailNotExists
            );
        }

        return $next($command);
    }
}
