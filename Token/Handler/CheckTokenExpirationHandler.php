<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Auth\Token\RefreshTokenCommand;
use App\Identity\Application\Auth\Token\RefreshTokenError;
use App\Shared\Application\Result\Result;

final class CheckTokenExpirationHandler extends Handler
{
    /**
     * @phpstan-param RefreshTokenCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     */
    public function handle(
        RefreshTokenCommand $command, \Closure $next): mixed
    {
        /** @phpstan-var \App\Identity\Domain\Token $oldToken */
        $oldToken = $command->oldToken;

        if ($oldToken->expiresAt
            && $oldToken->expiresAt <= new \DateTimeImmutable()
        ) {
            return Result::failure(
                error: RefreshTokenError::Expired
            );
        }

        return $next($command);
    }
}
