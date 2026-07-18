<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Forgot\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Password\Forgot\ForgotPasswordCommand;
use App\Identity\Application\Password\Forgot\Exception\EmailNotFoundException;
use App\Identity\Domain\Access\Auth\UserProviderInterface;
use App\Identity\Domain\Email\Email;

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
     * @throws EmailNotFoundException
     */
    public function handle(
        ForgotPasswordCommand $command, \Closure $next): mixed
    {
        $email = Email::of(value: $command->email);
        $credentials = ['email' => $email->value()];

        $user = $this->userProvider->retrieveByCredentials(
            credentials: $credentials
        );

        if (is_null(value: $user)) {
            throw new EmailNotFoundException();
        }

        $command->email = $email->value();

        return $next($command);
    }
}
