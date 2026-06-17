<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\RefreshToken\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Auth\RefreshToken\RefreshTokenCommand;
use App\Identity\Domain\Repository\TokenRepositoryInterface;
use App\Shared\Application\Handler\HandlerException;
use App\Identity\Application\Auth\RefreshToken\RefreshTokenError;
use App\Shared\Application\Result\Result;

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
     * @throws HandlerException<RefreshTokenError>
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

        throw new HandlerException(
            error: RefreshTokenError::TokenRevoked
        );
    }
}
