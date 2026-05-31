<?php declare(strict_types=1);

namespace App\Shared\Domain\Date;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

/**
 * @phpstan-template TUpdatedDate as \DateTimeImmutable|null
 */
#[ORM\HasLifecycleCallbacks]
trait UpdatedDateProvider
{
    /**
     * @phpstan-var \DateTimeImmutable|null
     */
    #[ORM\Column(
        name: 'updated_at',
        type: Types::DATETIME_IMMUTABLE, options: [
            'precision' => 6
        ],
        nullable: true
    )]
    public private(set) ?\DateTimeImmutable $updatedAt = null;
    
    /**
     * @phpstan-return void
     */
    #[ORM\PreUpdate]
    public function initializeUpdatedAt(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
