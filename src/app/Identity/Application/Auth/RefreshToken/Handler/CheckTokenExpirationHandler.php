<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\RefreshToken\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Auth\RefreshToken\RefreshTokenCommand;
use App\Shared\Application\Handler\HandlerException;
use App\Identity\Application\Auth\RefreshToken\RefreshTokenError;
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
                error: RefreshTokenError::TokenNotExists
            );
        }

        if ($oldToken->expiresAt !== null
            && $oldToken->expiresAt
            <= new \DateTimeImmutable()
        ) {
            throw new HandlerException(
                error: RefreshTokenError::TokenExpired
            );
        }

        return $next($command);
    }
}
