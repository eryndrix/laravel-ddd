<?php declare(strict_types=1);

namespace App\Identity\Application\RefreshToken\Handler;

use App\Identity\Application\RefreshToken\RefreshTokenCommand;
use App\Shared\Domain\Repository\TokenRepositoryInterface;

final class RevokeOldTokenHandler
{
    /**
     * Constructs a new RevokeOldTokenHandler instance.
     *
     * @param \App\Identity\Domain\Repository\TokenRepositoryInterface $repository
     */
    public function __construct(
        private TokenRepositoryInterface $repository
    ) {}

    /**
     * Revokes the old token in the refresh token command.
     *
     * @param RefreshTokenCommand $command
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(
        RefreshTokenCommand $command, \Closure $next): mixed
    {
        $oldToken = $command->oldToken;

        $oldToken->markAsUsed();
        $oldToken->revoke();

        $this->repository->save(token: $oldToken);

        return $next($command);
    }
}
