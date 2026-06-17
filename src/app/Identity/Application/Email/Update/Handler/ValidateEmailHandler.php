<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Update\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Email\Update\UpdateEmailCommand;
use App\Shared\Application\Handler\HandlerException;
use App\Identity\Application\Email\Update\UpdateEmailError;
use App\Shared\Domain\Email\Email;

final class ValidateEmailHandler extends Handler
{
    /**
     * @phpstan-param UpdateEmailCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws HandlerException<UpdateEmailError>
     */
    public function handle(
        UpdateEmailCommand $command, \Closure $next): mixed
    {
        try {
            $authUser = $command->user;
            /** @phpstan-var \App\Identity\Infrastructure\Auth\UserAdapter $authUser */
            $user = $authUser->unwrap();

            $newEmail = Email::of(value: $command->email);
            /** @phpstan-var \App\Shared\Domain\Email\Email $oldEmail */
            $oldEmail = $user->email;

            if ($oldEmail->value() === $newEmail->value()) {
                throw new HandlerException(
                    error: UpdateEmailError::EmailSameAsCurrent
                );
            }
        }

        catch (\DomainException $e) {
            throw new HandlerException(
                error: UpdateEmailError::InvalidEmailFormat
            );
        }

        return $next($command);
    }
}
