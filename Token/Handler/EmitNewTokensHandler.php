<?php declare(strict_types=1);

namespace App\Identity\Application\RefreshToken\Handler;

use App\Identity\Application\RefreshToken\RefreshTokenCommand;
use App\Identity\Domain\Jwt\JwtTokenIssuerInterface;

final class EmitNewTokensHandler
{
    /**
     * Constructs a new EmitNewTokensHandler instance.
     *
     * @param JwtTokenIssuerInterface $jwtTokenIssuer
     */
    public function __construct(
        private JwtTokenIssuerInterface $jwtTokenIssuer
    ) {}

    /**
     * Issuing new JWT tokens for the refresh token command.
     *
     * @param RefreshTokenCommand $command
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(
        RefreshTokenCommand $command, \Closure $next): mixed
    {
        $tokens = $this->jwtTokenIssuer->issueTokensFor(
            user: $command->user
        );
        
        $command->accessToken = $tokens->accessToken;
        $command->aTtl = $tokens->accessTokenTtl;
        $command->refreshToken = $tokens->refreshToken;
        $command->rTtl = $tokens->refreshTokenTtl;

        return $next($command);
    }
}