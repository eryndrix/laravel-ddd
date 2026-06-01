<?php declare(strict_types=1);

namespace App\Identity\Domain;

use Doctrine\ORM\Mapping as ORM;
use App\Shared\Domain\AggregateRoot;
use App\Identity\Domain\Changing\TokenChanger;
use App\Shared\Domain\Date\CreatedDateProvider;
use App\Shared\Domain\Date\UpdatedDateProvider;
use App\Shared\Domain\Id\{UserId, TokenId};
use Doctrine\DBAL\Types\Types;

/**
 * @phpstan-extends AggregateRoot<\App\Shared\Domain\Event>
 */
#[ORM\Entity]
#[ORM\Table(name: '`personal_access_tokens`')]
#[ORM\HasLifecycleCallbacks]
class Token extends AggregateRoot
{
    /**
     * @phpstan-use CreatedDateProvider<\DateTimeImmutable>
     */
    use CreatedDateProvider;

    /**
     * @phpstan-use UpdatedDateProvider<\DateTimeImmutable>
     */
    use UpdatedDateProvider;

    /**
     * @phpstan-var TokenId
     */
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: TokenId::class, unique: true)]
    public private(set) TokenId $id;

    /**
     * @phpstan-var class-string
     */
    #[ORM\Column(name: 'tokenable_type', type: Types::STRING)]
    public private(set) string $tokenableType {
        set (string $value) {
            /** @phpstan-var class-string $tokenableType */
            $tokenableType = trim(string: $value);
            $this->tokenableType = $tokenableType;
        }
    }

    /**
     * @phpstan-var UserId
     */
    #[ORM\Column(name: 'tokenable_id', type: UserId::class)]
    public private(set) UserId $tokenableId;

    /**
     * @phpstan-var string
     */
    #[ORM\Column(name: 'name', type: Types::STRING, length: 15)]
    public private(set) string $name {
        set (string $value) {
            $this->name = $value
                |> trim(...)
                |> (fn (string $name): string => mb_convert_case(
                    string: $name,
                    mode: MB_CASE_LOWER,
                    encoding: 'UTF-8'
                ));
        }
    }

    /**
     * @phpstan-var TokenHash
     */
    #[ORM\Embedded(class: TokenHash::class, columnPrefix: false)]
    public private(set) TokenHash $token;

    /**
     * @phpstan-var Abilities
     */
    #[ORM\Embedded(class: Abilities::class, columnPrefix: false)]
    public private(set) ?Abilities $abilities = null;

    /**
     * @phpstan-var \DateTimeImmutable|null
     */
    #[ORM\Column(
        name: 'last_used_at',
        type: Types::DATETIME_IMMUTABLE,
        precision: 6,
        nullable: true
    )]
    public private(set) ?\DateTimeImmutable $lastUsedAt = null;

    /**
     * @phpstan-var \DateTimeImmutable|null
     */
    #[ORM\Column(
        name: 'expires_at',
        type: Types::DATETIME_IMMUTABLE,
        precision: 6,
        nullable: true
    )]
    public private(set) ?\DateTimeImmutable $expiresAt = null;

    /**
     * @phpstan-param UserId $tokenableId
     * @phpstan-param string $name
     * @phpstan-param TokenHash $token
     * @phpstan-param class-string $tokenableType
     * @phpstan-param Abilities|null $abilities
     * @phpstan-param \DateTimeImmutable|null $expiresAt
     * @phpstan-param TokenId|null $id
     */
    public function __construct(
        UserId $tokenableId,
        string $name,
        TokenHash $token,
        string $tokenableType = User::class,
        ?Abilities $abilities = null,
        ?\DateTimeImmutable $expiresAt = null,
        ?TokenId $id = null,
    ) {
        /**
         * Generates a new TokenId if none is provided.
         */
        $this->id = $id ?? TokenId::generate();

        /**
         * Assigns token data properties.
         */
        $this->tokenableType = $tokenableType;
        $this->tokenableId = $tokenableId;
        $this->name = $name;
        $this->token = $token;
        $this->abilities = $abilities;
        $this->expiresAt = $expiresAt;
        
        /**
         * Initialize created_at and updated_at timestamps.
         */
        $this->initializeCreatedAt();
        $this->initializeUpdatedAt();
    }

    /**
     * @phpstan-return TokenChanger
     */
    public function beginChange(): TokenChanger
    {
        return new TokenChanger(token: $this);
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
     * @phpstan-param Abilities|null $abilities
     * @phpstan-return void
     */
    public function changeAbilities(?Abilities $abilities): void
    {
        $this->abilities = $abilities;
    }

    /**
     * @phpstan-param \DateTimeImmutable|null $lastUsedAt
     * @phpstan-return void
     */
    public function changeLastUsedAt(
        ?\DateTimeImmutable $lastUsedAt): void
    {
        $this->lastUsedAt = $lastUsedAt;
    }

    /**
     * @phpstan-param \DateTimeImmutable|null $expiresAt
     * @phpstan-return void
     */
    public function changeExpiresAt(
        ?\DateTimeImmutable $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }

    /**
     * @phpstan-return Token
     */
    public function endChange(): Token
    {
        return $this;
    }
}
