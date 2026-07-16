<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\User;

use App\Shared\Application\Handler;
use App\Shared\Application\Exception\UserNotFoundException;
use App\Identity\Domain\User;

final class UserHandler extends Handler
{
    /**
     * @phpstan-param UserQuery<User> $query
     * @phpstan-return UserData
     * 
     * @throws UserNotFoundException
     */
    public function execute(UserQuery $query): UserData
    {
        if (!$query->user instanceof User) {
            throw new UserNotFoundException();
        }

        return UserData::fromEntity(user: $query->user);
    }
}
