<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Auth\Login\LoginCommand;
use App\Shared\Domain\Id\UserId;
use App\Identity\Domain\Access\Auth\UserAdapterInterface;
use App\Identity\Domain\Access\TokenHash;
use App\Identity\Domain\Repository\TokenRepositoryInterface;
use App\Identity\Domain\Token;

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
     * @phpstan-param \Closure(LoginCommand):mixed $next
     *
     * @phpstan-return mixed
     */
    public function handle(
        LoginCommand $command, \Closure $next): mixed
    {
        /**
         * @phpstan-var array{
         *     access_token: string,
         *     ttl: int,
         *     refresh_token: string,
         *     refresh_ttl: \DateTimeImmutable
         * } $jwtTokenPair
         */
        $jwtTokenPair = $command->jwtTokenPair;
        
        /** @phpstan-var UserAdapterInterface $user */
        $user = $command->user;

        /** @phpstan-var UserId $userId */
        $userId = $user->getAuthIdentifier();
        
        $plainToken = $jwtTokenPair['refresh_token'];
        $expiresAt = $jwtTokenPair['refresh_ttl'];

        $token = Token::create(
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
