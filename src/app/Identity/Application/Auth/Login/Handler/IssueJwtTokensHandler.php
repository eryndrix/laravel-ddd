<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Auth\Login\LoginCommand;
use App\Identity\Application\Auth\Login\Exception\JwtTokenIssuanceException;
use App\Identity\Domain\Access\Auth\UserAdapterInterface;
use App\Identity\Domain\Access\Jwt\JwtTokenIssuerInterface;

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
     * @phpstan-param \Closure(LoginCommand):mixed $next
     *
     * @phpstan-return mixed
     *
     * @throws JwtTokenIssuanceException
     */
    public function handle(
        LoginCommand $command, \Closure $next): mixed
    {
        /** @phpstan-var UserAdapterInterface $user */
        $user = $command->user;
        
        try {
            $jwtTokenPair = $this->jwtTokenIssuer->issueTokensFor(
                user: $user
            );
        }

        catch (\RuntimeException $e) {
            throw new JwtTokenIssuanceException(previous: $e);
        }
        
        
        $command->jwtTokenPair = $jwtTokenPair;

        return $next($command);
    }
}
