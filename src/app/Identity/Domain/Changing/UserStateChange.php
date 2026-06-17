<?php declare(strict_types=1);

namespace App\Identity\Domain\Changing;

use App\Identity\Domain\Avatar;
use App\Shared\Domain\Email\Email;
use App\Identity\Domain\Password\Password;
use App\Identity\Domain\User;
/**
 * @phpstan-template TEntity of User
 */
trait UserStateChange
{
    /**
     * @phpstan-param Avatar|null $avatar
     * @phpstan-return void
     */
    public function changeAvatar(?Avatar $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @phpstan-param string $name
     * @phpstan-return void
     */
    public function changeName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @phpstan-param Email $email
     * @phpstan-return void
     */
    public function changeEmail(Email $email): void
    {
        $this->email = $email;
        $this->emailVerifiedAt = null;
    }

    /**
     * @phpstan-param Password $password
     * @phpstan-return void
     */
    public function changePassword(
        #[\SensitiveParameter] Password $password): void
    {
        $this->password = $password;
    }

    /**
     * @phpstan-param string|null $rememberToken
     */
    public function changeRememberToken(
        ?string $rememberToken): void
    {
        $this->rememberToken = $rememberToken;
    }
}
