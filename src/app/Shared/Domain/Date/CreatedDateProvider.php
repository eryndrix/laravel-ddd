<?php declare(strict_types=1);

namespace App\Shared\Domain\Date;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

/**
 * @phpstan-template TCreatedDate as \DateTimeImmutable|null
 */
#[ORM\HasLifecycleCallbacks]
trait CreatedDateProvider
{
    /**
     * @phpstan-var \DateTimeImmutable|null
     */
    #[ORM\Column(
        name: 'created_at',
        type: Types::DATETIME_IMMUTABLE, options: [
            'precision' => 6,
            'default' => 'CURRENT_TIMESTAMP'
        ]
    )]
    public private(set) ?\DateTimeImmutable $createdAt = null;

    /**
     * @phpstan-return void
     */
    #[ORM\PrePersist]
    public function initializeCreatedAt(): void
    {
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }
}
