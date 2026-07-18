<?php declare(strict_types=1);

namespace App\Identity\Application\Profile\Update\Password;

use App\Shared\Application\Command\Command;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\Casting\ObjectCast;
use WendellAdriel\ValidatedDTO\Attributes\Cast;
use App\Identity\Domain\User;

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
     * @phpstan-var User|null
     */
    #[Cast(type: ObjectCast::class, param: null)]
    public ?User $user;
    
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
