<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\User;

use App\Identity\Domain\User;
use App\Shared\Domain\Id\UserId;
use App\Identity\Domain\Avatar;
use App\Identity\Domain\Email\Email;
use App\Shared\Domain\Id\RoleId;

final readonly class UserData
{
    /**
     * @phpstan-param UserId $id
     * @phpstan-param Avatar|null $avatar
     * @phpstan-param string $firstName
     * @phpstan-param string $lastName
     * @phpstan-param Email $email
     * @phpstan-param RoleId $roleId
     */
    public function __construct(
        public private(set) UserId $id,
        public private(set) string $firstName,
        public private(set) string $lastName,
        public private(set) Email $email,
        public private(set) RoleId $roleId,
        public private(set) ?Avatar $avatar = null
    ) {}

    /**
     * @phpstan-param User $user
     * @phpstan-return self
     */
    public static function fromEntity(User $user): self
    {
        return new self(
            id: $user->id,
            firstName: $user->firstName,
            lastName: $user->lastName,
            email: $user->email,
            roleId: $user->roleId,
            avatar: $user->avatar
        );
    }
}
