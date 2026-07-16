<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Register;

use App\Shared\Application\Command\Command;
use App\Shared\Domain\Id\RoleId;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\Attributes\Cast;
use App\Identity\Domain\User;

final class RegisterCommand extends Command
{
    /**
     * @phpstan-var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $firstName;

    /**
     * @phpstan-var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $lastName;

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
    public ?RoleId $roleId = null;

    /**
     * @phpstan-var User|null
     */
    public ?User $user = null;
    
    /**
     * @phpstan-return array<string, string>
     */
    protected function mapData(): array
    {
        return [
            'first_name' => 'firstName',
            'last_name' => 'lastName'
        ];
    }
}
