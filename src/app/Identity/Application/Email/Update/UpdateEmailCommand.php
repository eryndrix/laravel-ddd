<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Update;

use App\Shared\Application\Command\Command;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\Casting\ObjectCast;
use WendellAdriel\ValidatedDTO\Attributes\Cast;
use Illuminate\Contracts\Auth\Authenticatable;

final class UpdateEmailCommand extends Command
{
    /**
     * @phpstan-var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $email;

    /**
     * @phpstan-var Authenticatable|null
     */
    #[Cast(type: ObjectCast::class, param: null)]
    public ?Authenticatable $user;
}
