<?php declare(strict_types=1);

namespace App\Privilege\Presentation;

use App\Shared\Presentation\Collection;

/**
 * @phpstan-extends Collection<RoleResource>
 */
final class RoleCollection extends Collection
{
    /**
     * @phpstan-var string
     */
    public $collects = RoleResource::class;
}
