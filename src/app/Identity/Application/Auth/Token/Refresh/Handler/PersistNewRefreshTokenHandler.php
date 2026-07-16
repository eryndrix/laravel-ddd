<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token\Refresh\Handler;

use App\Shared\Application\Handler;
use App\Identity\Domain\Token;
use App\Identity\Application\Auth\Token\Refresh\RefreshTokenCommand;
use App\Identity\Domain\Repository\TokenRepositoryInterface;
use App\Identity\Domain\Access\TokenHash;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Shared\Domain\Id\UserId;

final class PersistNewRefreshTokenHandler extends Handler
{
    /**
     * @phpstan-param TokenRepositoryInterface $repository
     */
    public function __construct(
        private TokenRepositoryInterface $repository
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
