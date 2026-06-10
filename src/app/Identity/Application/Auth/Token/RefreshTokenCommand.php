<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token;

use App\Shared\Application\Command\Command;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\Casting\ArrayCast;
use WendellAdriel\ValidatedDTO\Casting\ObjectCast;
use WendellAdriel\ValidatedDTO\Attributes\Cast;
use App\Identity\Domain\Token;
use Illuminate\Contracts\Auth\Authenticatable;

final class RefreshTokenCommand extends Command
{
    /**
     * @phpstan-var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $plainRefreshToken;

    /**
     * @phpstan-var Authenticatable|null
     */
    #[Cast(type: ObjectCast::class, param: null)]
    public ?Authenticatable $user;

    /**
     * @phpstan-var Token|null
     */
    #[Cast(type: ObjectCast::class, param: null)]
    public ?Token $oldToken;

    /**
     * @phpstan-var array<string, mixed>|null
     */
    #[Cast(type: ArrayCast::class, param: null)]
    public ?array $jwtTokenPair;
    
    /**
     * @phpstan-return array<string, string>
     */
    protected function mapData(): array
    {
        return [
            'plain_refresh_token' => 'plainRefreshToken'
        ];
    }
}
