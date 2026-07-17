<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token\Refresh\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Auth\Token\Refresh\Exception\TokenNotFoundException;
use App\Identity\Application\Auth\Token\Refresh\Exception\MissingAbilityException;
use App\Identity\Application\Auth\Token\Refresh\RefreshTokenCommand;

final class CheckTokenAbilitiesHandler extends Handler
{
    /**
     * @phpstan-param RefreshTokenCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     * 
     * @throws TokenNotFoundException
     * @throws MissingAbilityException
     */
    public function handle(
        RefreshTokenCommand $command, \Closure $next): mixed
    {
        /** @phpstan-var \App\Identity\Domain\Token $oldToken */
        $oldToken = $command->oldToken;

        if (is_null(value: $oldToken)) {
            throw new TokenNotFoundException();
        }

        $abilitiesData = $oldToken->abilities;
        
        if (is_null(value: $abilitiesData)) {
            throw new MissingAbilityException();
        }

        $abilities = $abilitiesData->value();

        if (!is_array(value: $abilities) || !in_array(
                needle: 'refresh',
                haystack: $abilities,
                strict: true
            )
        ) {
            throw new MissingAbilityException();
        }

        return $next($command);
    }
}
