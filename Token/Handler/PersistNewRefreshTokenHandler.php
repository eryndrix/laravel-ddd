<?php declare(strict_types=1);

namespace App\Identity\Application\RefreshToken\Handler;

use App\Shared\Domain\Repository\TokenRepositoryInterface;
use App\Identity\Domain\Claim\TokenHash;
use App\Identity\Application\RefreshToken\RefreshTokenCommand;
use App\Identity\Domain\Creating\TokenCreator;

final class PersistNewRefreshTokenHandler
{
    /**
     * Constructs a new PersistNewRefreshTokenHandler instance.
     *
     * @param \App\Identity\Domain\Repository\TokenRepositoryInterface $repository
     */
    public function __construct(
        private TokenRepositoryInterface $repository
    ) {}

    /**
     * Persists a new refresh token in the refresh token command.
     * 
     * @param RefreshTokenCommand $command
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(
        RefreshTokenCommand $command, \Closure $next): mixed
    {
        $expiresAt = new \DateTimeImmutable(
            datetime: '+ ' . $command->rTtl . ' minutes'
        );

        $token = TokenCreator::newRefreshToken(
            userId: $command->user->id,
            tokenHash: TokenHash::fromRawToken(
                rawToken: $command->refreshToken
            ),
            expiresAt: $expiresAt
        );

        $this->repository->save(token: $token);


        return $next($command);
    }
}
