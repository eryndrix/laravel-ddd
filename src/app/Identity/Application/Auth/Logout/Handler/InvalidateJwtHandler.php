<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Logout\Handler;

use App\Shared\Application\Handler\Handler;
use App\Identity\Domain\Access\Jwt\JwtTokenManagerInterface;
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
     * @phpstan-param \Closure $next
     */
    public function handle(
        LogoutCommand $command, \Closure $next): mixed
    {
        $this->jwtTokenManager->invalidateToken();

        return $next($command);
    }
}
