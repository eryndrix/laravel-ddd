<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Auth\Token\RefreshTokenCommand;
use App\Shared\Application\Handler\HandlerException;
use App\Identity\Application\Auth\Token\RefreshTokenError;
use App\Shared\Application\Result\Result;

final class CheckTokenExpirationHandler extends Handler
{
    /**
     * @phpstan-param RefreshTokenCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws HandlerException<RefreshTokenError>
     */
    public function handle(
        RefreshTokenCommand $command, \Closure $next): mixed
    {
        /** @phpstan-var \App\Identity\Domain\Token $oldToken */
        $oldToken = $command->oldToken;

        if (is_null(value: $oldToken)) {
            throw new HandlerException(
                error: RefreshTokenError::InvalidToken
            );
        }

        if ($oldToken->expiresAt !== null
            && $oldToken->expiresAt
            <= new \DateTimeImmutable()
        ) {
            throw new HandlerException(
                error: RefreshTokenError::Expired
            );
        }

        return $next($command);
    }
}
