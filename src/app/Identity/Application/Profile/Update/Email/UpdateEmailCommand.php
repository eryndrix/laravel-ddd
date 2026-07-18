<?php declare(strict_types=1);

namespace App\Identity\Application\Profile\Update\Email;

use App\Shared\Application\Command\Command;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\Attributes\Cast;
use App\Identity\Domain\User;

final class UpdateEmailCommand extends Command
{
    /**
     * @phpstan-var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $email;

    /**
     * @phpstan-var User|null
     */
    public ?User $user = null;
}
