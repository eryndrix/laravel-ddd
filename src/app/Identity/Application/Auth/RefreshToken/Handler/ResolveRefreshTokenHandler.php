<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\RefreshToken\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Application\Auth\RefreshToken\RefreshTokenCommand;
use App\Identity\Domain\Repository\TokenRepositoryInterface;
use App\Identity\Domain\TokenHash;
use App\Identity\Application\Auth\RefreshToken\RefreshTokenError;
use App\Shared\Application\Handler\HandlerException;

final class ResolveRefreshTokenHandler extends Handler
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
        try {
            $tokenHash = TokenHash::fromPlainToken(
                plainToken: $command->plainRefreshToken
            );
        }

        catch (\DomainException $e) {
            throw new HandlerException(
                error: RefreshTokenError::InvalidTokenFormat
            );
        }
        
        $oldToken = $this->repository->findByToken(
            tokenHash: $tokenHash
        );

        if (is_null(value: $oldToken)) {
            throw new HandlerException(
                error: RefreshTokenError::TokenNotExists
            );
        }

        $command->oldToken = $oldToken;

        return $next($command);
    }
}
