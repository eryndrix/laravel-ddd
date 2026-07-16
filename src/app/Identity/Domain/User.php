<?php declare(strict_types=1);

namespace App\Identity\Domain;

use Doctrine\ORM\Mapping as ORM;
use App\Shared\Domain\AggregateRoot;
use App\Identity\Domain\Changing\UserStateChange;
use App\Identity\Domain\Email\Email;
use App\Identity\Domain\Email\EmailVerification;
use App\Identity\Domain\Password\Password;
use App\Shared\Domain\Id\UserId;
use App\Shared\Domain\Id\RoleId;
use App\Shared\Domain\Date\CreatedDateProvider;
use App\Shared\Domain\Date\UpdatedDateProvider;
use App\Shared\Domain\Date\DeletedDateProvider;
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
     * @phpstan-use UserStateChange<$this>
     */
    use UserStateChange;
    
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
     * @phpstan-var Avatar|null
     */
    #[ORM\Column(name: 'avatar', type: Avatar::class, length: 255, nullable: true)]
    public private(set) ?Avatar $avatar = null;

    /**
     * @phpstan-var string
     * @throws \DomainException
     */
    #[ORM\Column(name: 'first_name', type: Types::STRING, length: 60)]
    public private(set) string $firstName {
        set (string $value) {
            $value = trim(string: $value);

            if ($value === '') {
                throw new \DomainException(
                    message: 'First name cannot be empty.'
                );
            }

            if (mb_strlen(string: $value) > 60) {
                throw new \DomainException(
                    message: 'First name is too long.'
                );
            }

            $this->firstName = mb_convert_case(
                string: $value,
                mode: MB_CASE_TITLE,
                encoding: 'UTF-8'
            );
        }
    }

    /**
     * @phpstan-var string
     * @throws \DomainException
     */
    #[ORM\Column(name: 'last_name', type: Types::STRING, length: 80)]
    public private(set) string $lastName {
        set (string $value) {
            $value = trim(string: $value);

            if ($value === '') {
                throw new \DomainException(
                    message: 'Last name cannot be empty.'
                );
            }

            if (mb_strlen(string: $value) > 80) {
                throw new \DomainException(
                    message: 'Last name is too long.'
                );
            }

            $this->lastName = mb_convert_case(
                string: $value,
                mode: MB_CASE_TITLE,
                encoding: 'UTF-8'
            );
        }
    }

    /**
     * @phpstan-var Email
     */
    #[ORM\Embedded(class: Email::class, columnPrefix: false)]
    public private(set) Email $email;

    /**
     * @phpstan-var \DateTimeImmutable|null
     */
    #[ORM\Column(
        name: 'email_verified_at',
        type: Types::DATETIME_IMMUTABLE,
        nullable: true
    )]
    private ?\DateTimeImmutable $emailVerifiedAt = null;

    /**
     * @phpstan-var Phone|null
     */
    #[ORM\Column(name: 'phone', type: Phone::class, length: 20, nullable: true)]
    public private(set) ?Phone $phone = null;

    /**
     * @phpstan-var \DateTimeImmutable|null
     */
    #[ORM\Column(
        name: 'phone_verified_at',
        type: Types::DATETIME_IMMUTABLE,
        precision: 6,
        nullable: true
    )]
    public private(set) ?\DateTimeImmutable $phoneVerifiedAt = null;

    /**
     * @phpstan-var bool
     */
    #[ORM\Column(name: 'is_active', type: Types::BOOLEAN, options: ['default' => true])]
    public private(set) bool $isActive = true;

    /**
     * @phpstan-var Password
     */
    #[ORM\Embedded(class: Password::class, columnPrefix: false)]
    public private(set) Password $password;

    /**
     * @phpstan-var \DateTimeImmutable|null
     */
    #[ORM\Column(
        name: 'password_changed_at',
        type: Types::DATETIME_IMMUTABLE,
        precision: 6,
        nullable: true
    )]
    public private(set) ?\DateTimeImmutable $passwordChangedAt = null;
    
    /**
     * @phpstan-var string|null
     * @throws \DomainException
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
            if ($value === null) {
                $this->rememberToken = null;
                return;
            }

            $rememberToken = trim(string: $value);

            if ($rememberToken === '') {
                throw new \DomainException(
                    message: 'Remember token cannot be empty.'
                );
            }

            if (mb_strlen(string: $rememberToken) > 100) {
                throw new \DomainException(
                    message: 'Remember token is too long.'
                );
            }

            $this->rememberToken = $rememberToken;
        }
    }

    /**
     * @phpstan-var \DateTimeImmutable|null
     */
    #[ORM\Column(
        name: 'last_login_at',
        type: Types::DATETIME_IMMUTABLE,
        precision: 6,
        nullable: true
    )]
    public private(set) ?\DateTimeImmutable $lastLoginAt = null;

    /**
     * @phpstan-var RoleId
     */
    #[ORM\Column(name: 'role_id', type: RoleId::class)]
    public private(set) RoleId $roleId;

    /**
     * @phpstan-param string $firstName
     * @phpstan-param string $lastName
     * @phpstan-param Email $email
     * @phpstan-param Password $password
     * @phpstan-param RoleId $roleId
     * @phpstan-param UserId|null $id
     */
    public function __construct(
        string $firstName,
        string $lastName,
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
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->roleId = $roleId;
        
        /**
         * Initialize created_at and updated_at timestamps.
         */
        $this->initializeCreatedAt();
        $this->initializeUpdatedAt();
    }
}
