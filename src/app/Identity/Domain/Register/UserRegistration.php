<?php declare(strict_types=1);

namespace App\Identity\Domain\Register;

use App\Identity\Domain\Email\Email;
use App\Identity\Domain\User;
use App\Identity\Domain\Password\Password;
use App\Shared\Domain\Id\RoleId;

final class UserRegistration
{
    /**
     * @phpstan-param string $firstName
     * @phpstan-param string $lastName
     * @phpstan-param Email $email
     * @phpstan-param Password $password
     * @phpstan-param RoleId $roleId
     */
    public static function new(
        string $firstName,
        string $lastName,
        Email $email,
        Password $password,
        RoleId $roleId
    ): User {
        $user = new User(
            firstName: $firstName,
            lastName: $lastName,
            email: $email,
            password: $password,
            roleId: $roleId
        );

        $user->resetEmailVerification();
        
        $user->record(
            event: new UserRegistered(
                userId: $user->id
            )
        );

        return $user;
    }
}
