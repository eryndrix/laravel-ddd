<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Auth\Login\LoginCommand;
use App\Identity\Domain\Repository\TokenRepositoryInterface;
use App\Identity\Domain\TokenHash;
use App\Identity\Domain\Creating\TokenCreator;
use App\Shared\Application\Result\Result;

final class PersistRefreshTokenHandler extends Handler
{
    /**
     * @phpstan-param TokenRepositoryInterface $repository
     */
    public function __construct(
        private TokenRepositoryInterface $repository
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
        /** @phpstan-var array<string, string> $tokenData*/
        $tokenData = $command->token;

        $rTtl = $tokenData['refresh_token_ttl'];
        $refreshToken = $tokenData['refresh_token'];

        $expiresAt = new \DateTimeImmutable(
            datetime: '+ ' . $rTtl . ' minutes'
        );

        /** @phpstan-var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = $command->user;

        /** @phpstan-var \App\Shared\Domain\Id\UserId $userId */
        $userId = $user->getAuthIdentifier();
        $token = TokenCreator::newRefreshToken(
            userId: $userId,
            tokenHash: TokenHash::fromPlainToken(
                plainToken: $refreshToken
            ),
            expiresAt: $expiresAt
        );

        $this->repository->save(token: $token);

        return $next($command);
    }
}
