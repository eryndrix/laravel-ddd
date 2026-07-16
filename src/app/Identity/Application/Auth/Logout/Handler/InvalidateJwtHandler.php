<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Logout\Handler;

use App\Shared\Application\Handler;
use App\Identity\Domain\Access\Jwt\JwtTokenManagerInterface;
use App\Identity\Application\Auth\Logout\Exception\JwtTokenInvalidationException;
use App\Identity\Application\Auth\Logout\LogoutCommand;

final class InvalidateJwtHandler extends Handler
{
    /**
     * @phpstan-param JwtTokenManagerInterface $jwtTokenManager
     */
    public function __construct(
        private JwtTokenManagerInterface $jwtTokenManager
    ) {}

    /**
     * @phpstan-param LogoutCommand $command
     * @phpstan-param \Closure(LogoutCommand):mixed $next
     *
     * @throws JwtTokenInvalidationException
     */
    public function handle(
        LogoutCommand $command, \Closure $next): mixed
    {
        try {
            $this->jwtTokenManager->invalidateToken();
        }

        catch (\RuntimeException $e) {
            throw new JwtTokenInvalidationException(
                previous: $e
            );
        }

        return $next($command);
    }
}
