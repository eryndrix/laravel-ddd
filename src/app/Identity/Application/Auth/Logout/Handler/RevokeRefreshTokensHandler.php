<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Logout\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Domain\Repository\TokenRepositoryInterface;
use App\Identity\Application\Auth\Logout\LogoutCommand;

final class RevokeRefreshTokensHandler extends Handler
{
    /**
     * @phpstan-param TokenRepositoryInterface $repository
     */
    public function __construct(
        private TokenRepositoryInterface $repository
    ) {}

    /**
     * @phpstan-param LogoutCommand $command
     * @phpstan-param \Closure $next
     * 
     * @phpstan-return mixed
     */
    public function handle(
        LogoutCommand $command, \Closure $next): mixed
    {
        /** @phpstan-var \App\Identity\Domain\User $user */
        $user = $command->user;

        $tokens = $this->repository->allByUserId(
            userId: $user->id
        );

        $revokedTokensCount = 0;

        foreach ($tokens as $token) {
            $expiresAt = $token->expiresAt;
            
            if (is_null(value: $expiresAt)
                || $expiresAt > new \DateTimeImmutable()
            ) {
                $token->markAsUsed();
                $this->repository->save(token: $token);
                $revokedTokensCount++;
            }
        }

        return $next($command);
    }
}
