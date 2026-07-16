<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login;

use App\Shared\Application\Command\Command;
use App\Identity\Domain\Access\Auth\UserAdapterInterface;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\Casting\BooleanCast;
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
     * @phpstan-var UserAdapterInterface|null
     */
    public ?UserAdapterInterface $user = null;

    /**
     * @phpstan-var array<string, mixed>|null
     */
    public ?array $jwtTokenPair = null;
    
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
