<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token\Refresh\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Auth\Token\Refresh\RefreshTokenCommand;
use App\Identity\Application\Token\Refresh\Exception\TokenReuseDetectedException;
use App\Identity\Domain\Repository\TokenRepositoryInterface;

final class DetectTokenReuseHandler extends Handler
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
     * 
     * @throws TokenReuseDetectedException
     */
    public function handle(
        RefreshTokenCommand $command, \Closure $next): mixed
    {
        /** @phpstan-var \App\Identity\Domain\Token $oldToken */
        $oldToken = $command->oldToken;

        if (!$oldToken->isRevoked()) {
            return $next($command);
        }

        $tokens = $this->repository->allByUserId(
            userId: $oldToken->tokenableId
        );

        $now = new \DateTimeImmutable();

        foreach ($tokens as $token) {
            if ($token->expiresAt > $now
                && !$token->isRevoked()
            ) {
                $token->revoke();
                $this->repository->save(token: $token);
            }
        }

        throw new TokenReuseDetectedException();
    }
}
