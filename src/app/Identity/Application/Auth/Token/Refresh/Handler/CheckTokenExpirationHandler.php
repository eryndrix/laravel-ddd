<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token\Refresh\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Auth\Token\Refresh\Exception\TokenExpiredException;
use App\Identity\Application\Auth\Token\Refresh\Exception\TokenNotFoundException;
use App\Identity\Application\Auth\Token\Refresh\RefreshTokenCommand;

final class CheckTokenExpirationHandler extends Handler
{
    /**
     * @phpstan-param RefreshTokenCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws TokenNotFoundException
     * @throws TokenExpiredException
     */
    public function handle(
        RefreshTokenCommand $command, \Closure $next): mixed
    {
        /** @phpstan-var \App\Identity\Domain\Token $oldToken */
        $oldToken = $command->oldToken;

        if (is_null(value: $oldToken)) {
            throw new TokenNotFoundException();
        }

        if ($oldToken->expiresAt !== null
            && $oldToken->expiresAt
            <= new \DateTimeImmutable()
        ) {
            throw new TokenExpiredException();
        }

        return $next($command);
    }
}
