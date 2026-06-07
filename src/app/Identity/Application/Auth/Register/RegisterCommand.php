<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Register;

use App\Shared\Application\Command\Command;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\Casting\ObjectCast;
use WendellAdriel\ValidatedDTO\Attributes\Cast;
use App\Shared\Domain\Id\RoleId;

final class RegisterCommand extends Command
{
    /**
     * @phpstan-var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $name;

    /**
     * @phpstan-var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $email;

    /**
     * @phpstan-var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $password;

    /**
     * @phpstan-var RoleId|null
     */
    #[Cast(type: ObjectCast::class, param: null)]
    public ?RoleId $roleId = null;
}
