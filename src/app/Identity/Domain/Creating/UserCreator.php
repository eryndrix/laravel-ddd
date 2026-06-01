<?php declare(strict_types=1);

namespace App\Identity\Domain\Creating;

use App\Identity\Domain\Avatar;
use App\Shared\Domain\Email\Email;
use App\Identity\Domain\User;
use App\Identity\Domain\Password\Password;
use App\Shared\Domain\Id\RoleId;

final class UserCreator
{
    /**
     * @phpstan-param string $name
     * @phpstan-param Email $email
     * @phpstan-param Password $password
     * @phpstan-param RoleId $roleId
     */
    public static function new(
        string $name,
        Email $email,
        Password $password,
        RoleId $roleId
    ): User {
        $user = new User(
            name: $name,
            email: $email,
            password: $password,
            roleId: $roleId
        );

        $user->resetEmailVerification();

        return $user;
    }
}
