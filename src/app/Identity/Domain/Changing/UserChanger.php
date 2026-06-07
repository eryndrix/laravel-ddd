<?php declare(strict_types=1);

namespace App\Identity\Domain\Changing;

use App\Identity\Domain\Avatar;
use App\Shared\Domain\Email\Email;
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
     * @phpstan-param Avatar|null $avatar
     * @phpstan-return self
     */
    public function avatar(?Avatar $avatar): self
    {
        if ($avatar !== null && $this->user->avatar 
            !== null && $this->user->avatar->equals(other: $avatar)
        ) {
            return $this;
        }

        $this->user->changeAvatar(avatar: $avatar);
        $this->isDirty = true;

        return $this;
    }

    /**
     * @phpstan-param string $name
     * @phpstan-return self
     * 
     * @throws \DomainException
     */
    public function name(string $name): self
    {
        if ($name === '') {
            throw new \DomainException(message: 'Name cannot be empty.');
        }

        if ($this->user->name === $name) {
            return $this;
        }

        $this->user->changeName(name: $name);
        $this->isDirty = true;

        return $this;
    }

    /**
     * @phpstan-param Email $email
     * @phpstan-return self
     */
    public function email(Email $email): self
    {
        if ($this->user->email->equals(other: $email)) {
            return $this;
        }

        $this->user->changeEmail(email: $email);
        $this->user->resetEmailVerification();
        $this->isDirty = true;

        return $this;
    }

    /**
     * @phpstan-param Password $newPassword
     * @phpstan-return self
     */
    public function password(Password $newPassword): self
    {
        if ($this->user->password->equals(other: $newPassword)) {
            return $this;
        }

        $this->user->changePassword(password: $newPassword);
        $this->isDirty = true;

        return $this;
    }
}
