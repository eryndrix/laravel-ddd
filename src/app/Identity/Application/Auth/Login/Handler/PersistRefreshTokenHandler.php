<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Auth\Login\LoginCommand;
use App\Identity\Domain\Repository\TokenRepositoryInterface;
use App\Identity\Domain\TokenHash;
use App\Identity\Domain\Creating\TokenCreator;
use App\Shared\Domain\Id\UserId;
use App\Identity\Application\Auth\Login\Output\LoginError;
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
        if (is_null(value: $command->user)) {
            return Result::failure(
                error: LoginError::InvalidCredentials
            );
        }

        /** @phpstan-var array{access_token: string, access_token_ttl: int, refresh_token: string, refresh_token_ttl: int} $tokenData */
        $tokenData = $command->token;

        $rTtl = $tokenData['refresh_token_ttl'];
        $refreshToken = $tokenData['refresh_token'];

        $expiresAt = new \DateTimeImmutable(
            datetime: '+ ' . $rTtl . ' minutes'
        );

        $identifier = $command->user->getAuthIdentifier();

        if (!is_string(value: $identifier)) {
            return $next($command);
        }

        $userId = UserId::of(value: $identifier);

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
