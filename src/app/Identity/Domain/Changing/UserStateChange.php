<?php declare(strict_types=1);

namespace App\Identity\Domain\Changing;

use App\Identity\Domain\User;
use App\Identity\Domain\Email\Email;
use App\Identity\Domain\Email\EmailChanged;
use App\Identity\Domain\Phone\Phone;
use App\Identity\Domain\Phone\PhoneChanged;
use App\Identity\Domain\Password\Password;
use App\Identity\Domain\Password\PasswordChanged;
use App\Identity\Domain\Avatar;

/**
 * @phpstan-template TEntity of User
 * 
 * @property Avatar|null $avatar
 * @property string $firstName
 * @property string $lastName
 * @property Email $email
 * @property \DateTimeImmutable|null $emailVerifiedAt
 * @property Password $password
 * @property \DateTimeImmutable|null $passwordChangedAt
 * @property string|null $rememberToken
 */
trait UserStateChange
{
    /**
     * @phpstan-param Avatar|null $avatar
     * @phpstan-return void
     */
    public function changeAvatar(?Avatar $avatar): void
    {
        if ($avatar !== null && $this->avatar !== null
            && $this->avatar->equals(other: $avatar)
        ) {
            return;
        }

        $this->avatar = $avatar;
    }

    /**
     * @phpstan-param string $firstName
     * @phpstan-return void
     */
    public function changeFirstName(string $firstName): void
    {
        if ($this->firstName === $firstName) {
            return;
        }

        $this->firstName = $firstName;
    }

    /**
     * @phpstan-param string $lastName
     * @phpstan-return void
     */
    public function changeLastName(string $lastName): void
    {
        if ($this->lastName === $lastName) {
            return;
        }

        $this->lastName = $lastName;
    }

    /**
     * @phpstan-param Email $email
     * @phpstan-return void
     */
    public function changeEmail(Email $email): void
    {
        if ($this->email->equals(other: $email)) {
            return;
        }

        $this->email = $email;
        $this->emailVerifiedAt = null;
        
        $this->record(
            event: new EmailChanged(userId: $this->id)
        );
    }

    /**
     * @phpstan-param Phone $phone
     * @phpstan-return void
     */
    public function changePhone(Phone $phone): void
    {
        if ($this->phone->equals(other: $phone)) {
            return;
        }

        $this->phone = $phone;
        $this->phoneVerifiedAt = null;
        
        $this->record(
            event: new PhoneChanged(userId: $this->id)
        );
    }

    /**
     * @phpstan-return void
     */
    public function resetEmailVerification(): void
    {
        $this->emailVerifiedAt = null;
    }

    /**
     * @phpstan-return void
     */
    public function markEmailAsVerified(): void
    {
        $this->emailVerifiedAt = new \DateTimeImmutable();
    }

    /**
     * @phpstan-return bool
     */
    public function isEmailVerified(): bool
    {
        return $this->emailVerifiedAt !== null;
    }
    
    /**
     * @phpstan-return void
     */
    public function activate(): void
    {
        if ($this->isActive) {
            return;
        }

        $this->isActive = true;
    }

    /**
     * @phpstan-return void
     */
    public function deactivate(): void
    {
        if (!$this->isActive) {
            return;
        }

        $this->isActive = false;
    }

    /**
     * @phpstan-param Password $password
     * @phpstan-return void
     */
    public function changePassword(
        #[\SensitiveParameter] Password $password): void
    {
        if ($this->password->equals(other: $password)) {
            return;
        }

        $this->password = $password;
        $this->passwordChangedAt = new \DateTimeImmutable();
        
        $this->record(
            event: new PasswordChanged(userId: $this->id)
        );
    }

    /**
     * @phpstan-param string|null $rememberToken
     * @phpstan-return void
     */
    public function changeRememberToken(
        ?string $rememberToken): void
    {
        if ($rememberToken !== null) {
            $rememberToken = trim(string: $rememberToken);
            
            if ($rememberToken === '') {
                $rememberToken = null;
            }
        }

        if ($this->rememberToken === $rememberToken) {
            return;
        }

        $this->rememberToken = $rememberToken;
    }

    /**
     * @phpstan-return void
     */
    public function markAsLoggedIn(): void
    {
        $this->lastLoginAt = new \DateTimeImmutable();
    }
}
