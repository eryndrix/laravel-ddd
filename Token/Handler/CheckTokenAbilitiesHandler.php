<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Auth\Token\RefreshTokenCommand;
use App\Identity\Application\Auth\Token\RefreshTokenError;
use App\Shared\Application\Result\Result;

final class CheckTokenAbilitiesHandler extends Handler
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
        $abilities = $oldToken->abilities->value();

        if (!is_array(value: $abilities) || !in_array(
                needle: 'refresh',
                haystack: $abilities,
                strict: true
            )
        ) {
            return Result::failure(
                error: RefreshTokenError::MissingAbility
            );
        }

        return $next($command);
    }
}
