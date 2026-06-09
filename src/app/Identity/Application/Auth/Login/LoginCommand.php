<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login;

use App\Shared\Application\Command\Command;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\Casting\BooleanCast;
use WendellAdriel\ValidatedDTO\Casting\ObjectCast;
use Illuminate\Contracts\Auth\Authenticatable;
use WendellAdriel\ValidatedDTO\Casting\ArrayCast;
use WendellAdriel\ValidatedDTO\Attributes\Cast;

final class LoginCommand extends Command
{
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
     * @phpstan-var bool
     */
    #[Cast(type: BooleanCast::class, param: null)]
    public bool $rememberMe = false;

    /**
     * @phpstan-var Authenticatable
     */
    #[Cast(type: ObjectCast::class, param: null)]
    public ?Authenticatable $user;

    /**
     * @phpstan-var array<string, mixed>
     */
    #[Cast(type: ArrayCast::class, param: null)]
    public ?array $token;
    
    /**
     * @phpstan-return array<string, string>
     */
    protected function mapData(): array
    {
        return [
            'remember_me' => 'rememberMe'
        ];
    }
}
