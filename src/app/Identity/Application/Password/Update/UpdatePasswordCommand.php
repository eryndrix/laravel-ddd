<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Update;

use App\Shared\Application\Command\Command;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\Casting\ObjectCast;
use WendellAdriel\ValidatedDTO\Attributes\Cast;
use Illuminate\Contracts\Auth\Authenticatable;

final class UpdatePasswordCommand extends Command
{
    /**
     * @phpstan-var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $password;

    /**
     * @phpstan-var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $passwordConfirmation;

    /**
     * @phpstan-var Authenticatable|null
     */
    #[Cast(type: ObjectCast::class, param: null)]
    public ?Authenticatable $user;
    
    /**
     * @phpstan-return array<string, string>
     */
    protected function mapData(): array
    {
        return [
            'password_confirmation' => 'passwordConfirmation'
        ];
    }
}
