<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Auth\Token\RefreshTokenCommand;
use App\Shared\Application\Handler\HandlerException;
use App\Identity\Application\Auth\Token\RefreshTokenError;
use App\Shared\Application\Result\Result;

final class CheckTokenAbilitiesHandler extends Handler
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

        $abilitiesData = $oldToken->abilities;
        
        if (is_null(value: $abilitiesData)) {
            throw new HandlerException(
                error: RefreshTokenError::MissingAbility
            );
        }

        $abilities = $abilitiesData->value();

        if (!is_array(value: $abilities) || !in_array(
                needle: 'refresh',
                haystack: $abilities,
                strict: true
            )
        ) {
            throw new HandlerException(
                error: RefreshTokenError::MissingAbility
            );
        }

        return $next($command);
    }
}
