<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login\Handler;

use App\Shared\Application\Handler;
use App\Identity\Domain\Repository\TokenRepositoryInterface;
use App\Identity\Application\Auth\Login\LoginCommand;
use App\Identity\Application\Auth\Login\Output\LoginError;
use App\Shared\Domain\Id\UserId;
use App\Shared\Application\Result\Result;

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
        if (is_null(value: $command->user)) {
            return Result::failure(
                error: LoginError::SystemError
            );
        }

        $identifier = $command->user->getAuthIdentifier();

        if (!is_string(value: $identifier)) {
            return Result::failure(error: LoginError::SystemError);
        }

        $userId = UserId::of(value: $identifier);
        $tokens = $this->repository->allByUserId(userId: $userId);
        $now = new \DateTimeImmutable();

        foreach ($tokens as $token) {
            if ($token->expiresAt > $now) {
                $token->revoke();
                $this->repository->save(token: $token);
            }
        }

        return $next($command);
    }
}
