<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token\Refresh\Handler;

use App\Shared\Application\Handler;
use App\Identity\Application\Auth\Token\Refresh\RefreshTokenCommand;
use App\Identity\Application\Auth\Token\Refresh\Exception\TokenNotFoundException;
use App\Identity\Domain\Repository\TokenRepositoryInterface;

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
     * @throws TokenNotFoundException
     */
    public function handle(
        RefreshTokenCommand $command, \Closure $next): mixed
    {
        /** @phpstan-var \App\Identity\Domain\Token $oldToken */
        $oldToken = $command->oldToken;

        if (is_null(value: $oldToken)) {
            throw new TokenNotFoundException();
        }

        $oldToken->markAsUsed();
        $oldToken->revoke();

        $this->repository->save(token: $oldToken);

        return $next($command);
    }
}
