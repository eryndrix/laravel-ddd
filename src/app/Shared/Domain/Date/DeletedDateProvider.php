<?php declare(strict_types=1);

namespace App\Shared\Domain\Date;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

/**
 * @phpstan-template TDeletedDate as \DateTimeImmutable|null
 */
#[ORM\HasLifecycleCallbacks]
trait DeletedDateProvider
{
    /**
     * @phpstan-var \DateTimeImmutable|null
     */
    #[ORM\Column(
        name: 'deleted_at',
        type: Types::DATETIME_IMMUTABLE,
        nullable: true
    )]
    public private(set) ?\DateTimeImmutable $deletedAt = null;

    /**
     * @phpstan-return void
     */
    public function softDelete(): void
    {
        $this->deletedAt = new \DateTimeImmutable();
    }

    /**
     * @phpstan-return void
     */
    public function restore(): void
    {
        $this->deletedAt = null;
    }
}
