<?php declare(strict_types=1);

namespace App\Identity\Domain\Password;

use App\Shared\Domain\Event;
use App\Shared\Domain\Id\UserId;

/**
 * @phpstan-extends Event<UserId>
 */
final class PasswordChanged extends Event
{
    /**
     * @phpstan-param UserId $userId
     */
    public function __construct(
        public private(set) UserId $userId
    ) {}
}
