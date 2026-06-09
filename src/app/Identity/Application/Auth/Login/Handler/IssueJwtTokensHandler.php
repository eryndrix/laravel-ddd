<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Auth\Login\LoginCommand;
use App\Identity\Domain\Access\Jwt\JwtTokenIssuerInterface;
use App\Shared\Application\Result\Result;

final class IssueJwtTokensHandler extends Handler
{
    /**
     * @phpstan-param JwtTokenIssuerInterface $jwtTokenIssuer
     */
    public function __construct(
        private JwtTokenIssuerInterface $jwtTokenIssuer
    ) {}

    /**
     * @phpstan-param LoginCommand $command
     * @phpstan-param \Closure $next
     *
     * @phpstan-return mixed
     */
    public function handle(
        LoginCommand $command, \Closure $next): mixed
    {
        /** @phpstan-var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = $command->user;

        $token = $this->jwtTokenIssuer->issueTokensFor(
            user: $user
        );
        
        $command->token = $token;

        return $next($command);
    }
}
