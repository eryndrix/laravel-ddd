<?php declare(strict_types=1);

namespace App\Shared\Domain;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

/**
 * @phpstan-template TStatus of bool
 */
#[ORM\MappedSuperclass]
trait Activatable
{
    /**
     * @phpstan-var TStatus
     */
    #[ORM\Column(
        name: 'is_active', type: Types::BOOLEAN, options: [
            'default' => true
        ]
    )]
    public private(set) bool $isActive = true;

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
}
