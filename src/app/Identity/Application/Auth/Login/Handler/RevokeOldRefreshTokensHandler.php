<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Auth\Login\LoginCommand;
use App\Identity\Domain\Repository\TokenRepositoryInterface;
use App\Shared\Application\Result\Result;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Shared\Domain\Id\UserId;

final class RevokeOldRefreshTokensHandler extends Handler
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
        /** @phpstan-var Authenticatable $user */
        $user = $command->user;

        /** @phpstan-var UserId $userId */
        $userId = $user->getAuthIdentifier();

        $tokens = $this->repository->allByUserId(
            userId: $userId
        );

        $now = new \DateTimeImmutable();

        foreach ($tokens as $token) {
            if ($token->expiresAt < $now) {
                $token->revoke();
                $this->repository->save(token: $token);
            }
        }

        return $next($command);
    }
}
