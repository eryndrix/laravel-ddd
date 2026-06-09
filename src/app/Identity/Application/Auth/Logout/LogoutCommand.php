<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Logout;

use App\Identity\Domain\User;

final class LogoutCommand
{
    /**
     * @phpstan-param User $user
     */
    public function __construct(
        public private(set) User $user
    ) {}
}
