<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Forgot\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Password\Forgot\ForgotPasswordCommand;
use App\Shared\Domain\Email\Email;

final class ValidateEmailFormatHandler extends Handler
{
    /**
     * @phpstan-param ForgotPasswordCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     */
    public function handle(
        ForgotPasswordCommand $command, \Closure $next): mixed
    {
        try {
            $email = Email::of(value: $command->email);
            $command->email = $email->value();
        }

        catch (\DomainException $e) {
            $command->emailExists = false;
            return $next($command);
        }

        return $next($command);
    }
}
