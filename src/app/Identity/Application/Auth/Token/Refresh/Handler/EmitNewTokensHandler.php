<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token\Refresh\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Auth\Token\Refresh\RefreshTokenCommand;
use App\Identity\Domain\Access\Jwt\JwtTokenIssuerInterface;
use App\Identity\Application\Auth\Token\Refresh\Exception\TokenIssuanceException;
use Illuminate\Contracts\Auth\Authenticatable;

final class EmitNewTokensHandler extends Handler
{
    /**
     * @phpstan-param JwtTokenIssuerInterface $jwtTokenIssuer
     */
    public function __construct(
        private JwtTokenIssuerInterface $jwtTokenIssuer
    ) {}

    /**
     * @phpstan-param RefreshTokenCommand $command
     * @phpstan-param \Closure $next
     *
     * @phpstan-return mixed
     * 
     * @throws TokenIssuanceException
     */
    public function handle(
        RefreshTokenCommand $command, \Closure $next): mixed
    {
        /** @phpstan-var Authenticatable $user */
        $user = $command->user;

        try {
            $jwtTokenPair = $this->jwtTokenIssuer->issueTokensFor(
                user: $user
            );
        }

        catch (\RuntimeException $e) {
            throw new TokenIssuanceException(previous: $e);
        }
        
        $command->jwtTokenPair = $jwtTokenPair;

        return $next($command);
    }
}
