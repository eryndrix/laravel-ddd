<?php declare(strict_types=1);

namespace App\Identity\Domain;

use Doctrine\ORM\Mapping as ORM;
use App\Shared\Domain\AggregateRoot;
use App\Identity\Domain\Changing\UserChanger;
use App\Shared\Domain\Date\CreatedDateProvider;
use App\Shared\Domain\Date\UpdatedDateProvider;
use App\Shared\Domain\Date\DeletedDateProvider;
use App\Shared\Domain\Activatable;
use App\Shared\Domain\Email\Email;
use App\Shared\Domain\Email\EmailVerification;
use App\Shared\Domain\Id\UserId;
use App\Shared\Domain\Id\RoleId;
use App\Identity\Domain\Password\Password;
use Doctrine\DBAL\Types\Types;

/**
 * @phpstan-extends AggregateRoot<\App\Shared\Domain\Event>
 */
#[ORM\Entity]
#[ORM\Table(name: '`users`')]
#[ORM\HasLifecycleCallbacks]
class User extends AggregateRoot
{
    /**
     * @phpstan-use Activatable<bool>
     */
    use Activatable;

    /**
     * @phpstan-use EmailVerification<\DateTimeImmutable|null>
     */
    use EmailVerification;
    
    /**
     * @phpstan-use CreatedDateProvider<\DateTimeImmutable>
     */
    use CreatedDateProvider;

    /**
     * @phpstan-use UpdatedDateProvider<\DateTimeImmutable>
     */
    use UpdatedDateProvider;

    /**
     * @phpstan-use DeletedDateProvider<\DateTimeImmutable>
     */
    use DeletedDateProvider;

    /**
     * @phpstan-var UserId
     */
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: UserId::class, unique: true)]
    public private(set) UserId $id;

    /**
     * @phpstan-var RoleId
     */
    #[ORM\Column(name: 'role_id', type: RoleId::class)]
    public private(set) RoleId $roleId;

    /**
     * @phpstan-var Avatar|null
     */
    #[ORM\Embedded(class: Avatar::class, columnPrefix: false)]
    public private(set) ?Avatar $avatar = null;

    /**
     * @phpstan-var string
     */
    #[ORM\Column(name: 'name', type: Types::STRING, length: 61)]
    public private(set) string $name {
        set (string $value) {
            $this->name = $value
                |> trim(...)
                |> (fn (string $name): string => mb_convert_case(
                    string: $name,
                    mode: MB_CASE_TITLE,
                    encoding: 'UTF-8'
                ));
        }
    }

    /**
     * @phpstan-var Email
     */
    #[ORM\Embedded(class: Email::class, columnPrefix: false)]
    public private(set) Email $email;

    /**
     * @phpstan-var Password
     */
    #[ORM\Embedded(class: Password::class, columnPrefix: false)]
    public private(set) Password $password;
    
    /**
     * @phpstan-var string|null
     */
    #[ORM\Column(
        name: 'remember_token',
        type: Types::STRING,
        length: 100,
        unique: true,
        nullable: true
    )]
    public private(set) ?string $rememberToken = null {
        set (string|null $value) {
            $rememberToken = $value !== null
                ? trim(string: $value)
                : null;

            $this->rememberToken = $rememberToken;
        }
    }

    /**
     * @phpstan-param string $name
     * @phpstan-param Email $email
     * @phpstan-param Password $password
     * @phpstan-param RoleId $roleId
     * @phpstan-param UserId|null $id
     */
    public function __construct(
        string $name,
        Email $email,
        Password $password,
        RoleId $roleId,
        ?UserId $id = null
    ) {
        /**
         * Generates a new UserId if none is provided.
         */
        $this->id = $id ?? UserId::generate();

        /**
         * Assigns user data properties.
         */
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->roleId = $roleId;
        
        /**
         * Initialize created_at and updated_at timestamps.
         */
        $this->initializeCreatedAt();
        $this->initializeUpdatedAt();
    }
    
    /**
     * @phpstan-return UserChanger
     */
    public function beginChange(): UserChanger
    {
        return new UserChanger(user: $this);
    }
    
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
    public function changePassword(Password $password): void
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

    /**
     * @phpstan-return User
     */
    public function endChange(): User
    {
        return $this;
    }
}
