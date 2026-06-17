<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\RefreshToken\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Auth\RefreshToken\RefreshTokenCommand;
use App\Identity\Domain\Repository\TokenRepositoryInterface;
use App\Shared\Application\Handler\HandlerException;
use App\Identity\Application\Auth\RefreshToken\RefreshTokenError;

final class RevokeOldTokenHandler extends Handler
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

        if (is_null(value: $oldToken)) {
            throw new HandlerException(
                error: RefreshTokenError::TokenNotExists
            );
        }

        $oldToken->markAsUsed();
        $oldToken->revoke();

        $this->repository->save(token: $oldToken);

        return $next($command);
    }
}
