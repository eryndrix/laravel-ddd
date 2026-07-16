<?php declare(strict_types=1);

namespace App\Identity\Domain\Changing;

use App\Identity\Domain\Avatar;
use App\Identity\Domain\Email\Email;
use App\Identity\Domain\Password\Password;
use App\Identity\Domain\User;

final class UserChanger
{
    /**
     * @phpstan-var bool
     */
    public private(set) bool $isDirty = false;

    /**
     * @phpstan-param User $user
     */
    public function __construct(
        private User $user
    ) {}

    /**
     * @phpstan-return self
     */
    public function beginChange(): self
    {
        return new self(user: $this->user);
    }

    /**
     * @phpstan-param Avatar|null $avatar
     * @phpstan-return self
     */
    public function avatar(?Avatar $avatar): self
    {
        $this->user->changeAvatar(avatar: $avatar);
        $this->isDirty = true;

        return $this;
    }

    /**
     * @phpstan-param string $firstName
     * @phpstan-return self
     */
    public function firstName(string $firstName): self
    {
        $this->user->changeFirstName(firstName: $firstName);
        $this->isDirty = true;

        return $this;
    }

    /**
     * @phpstan-param string $lastName
     * @phpstan-return self
     */
    public function changeLastName(string $lastName): self
    {
        $this->user->changeLastName(lastName: $lastName);
        $this->isDirty = true;

        return $this;
    }

    /**
     * @phpstan-param Email $email
     * @phpstan-return self
     */
    public function email(Email $email): self
    {
        $this->user->changeEmail(email: $email);
        $this->isDirty = true;

        return $this;
    }

    /**
     * @phpstan-param Password $newPassword
     * @phpstan-return self
     */
    public function password(Password $newPassword): self
    {
        $this->user->changePassword(password: $newPassword);
        $this->isDirty = true;

        return $this;
    }

    /**
     * @phpstan-return User
     */
    public function endChange(): User
    {
        return $this->user;
    }
}
