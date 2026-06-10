<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Auth\Token\RefreshTokenCommand;
use App\Identity\Domain\Access\Jwt\JwtTokenIssuerInterface;
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
     */
    public function handle(
        RefreshTokenCommand $command, \Closure $next): mixed
    {
        /** @phpstan-var Authenticatable $user */
        $user = $command->user;

        $jwtTokenPair = $this->jwtTokenIssuer->issueTokensFor(
            user: $user
        );
        
        $command->jwtTokenPair = $jwtTokenPair;

        return $next($command);
    }
}