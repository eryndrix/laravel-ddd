<?php declare(strict_types=1);

namespace App\Shared\Domain\Email;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

/**
 * @phpstan-template TEmailVerifiedAt of \DateTimeImmutable|null
 */
#[ORM\MappedSuperclass]
trait EmailVerification
{
    /**
     * @phpstan-var TEmailVerifiedAt
     */
    #[ORM\Column(
        name: 'email_verified_at',
        type: Types::DATETIME_IMMUTABLE,
        nullable: true
    )]
    private ?\DateTimeImmutable $emailVerifiedAt = null;

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
    public function verifyEmail(): void
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
}
