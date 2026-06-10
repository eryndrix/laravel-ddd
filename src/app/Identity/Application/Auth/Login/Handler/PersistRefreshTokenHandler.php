<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Auth\Login\LoginCommand;
use App\Identity\Domain\Repository\TokenRepositoryInterface;
use App\Identity\Domain\TokenHash;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Shared\Domain\Id\UserId;
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
        /**
         * @phpstan-var array{
         *     refresh_token: string,
         *     refresh_ttl: \DateTimeImmutable
         * } $jwtTokenPair
         */
        $jwtTokenPair = $command->jwtTokenPair;

        /** @phpstan-var Authenticatable $user */
        $user = $command->user;

        /** @phpstan-var UserId $userId */
        $userId = $user->getAuthIdentifier();
        
        $plainToken = $jwtTokenPair['refresh_token'];
        $expiresAt = $jwtTokenPair['refresh_ttl'];

        $token = TokenCreator::newRefreshToken(
            userId: $userId,
            tokenHash: TokenHash::fromPlainToken(
                plainToken: $plainToken
            ),
            expiresAt: $expiresAt
        );

        $this->repository->save(token: $token);

        return $next($command);
    }
}
