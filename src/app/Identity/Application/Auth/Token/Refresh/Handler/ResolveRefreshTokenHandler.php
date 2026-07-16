<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token\Refresh\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Auth\Token\Refresh\RefreshTokenCommand;
use App\Identity\Application\Token\Refresh\Exception\TokenNotFoundException;
use App\Identity\Domain\Repository\TokenRepositoryInterface;
use App\Identity\Domain\Access\TokenHash;

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
     * @throws TokenNotFoundException
     */
    public function handle(
        RefreshTokenCommand $command, \Closure $next): mixed
    {
        $tokenHash = TokenHash::fromPlainToken(
            plainToken: $command->plainRefreshToken
        );
        
        $oldToken = $this->repository->findByToken(
            tokenHash: $tokenHash
        );

        if (is_null(value: $oldToken)) {
            throw new TokenNotFoundException();
        }

        $command->oldToken = $oldToken;

        return $next($command);
    }
}
