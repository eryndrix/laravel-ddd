<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token;

use App\Shared\Application\Command\Command;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\Attributes\Cast;

final class RefreshTokenCommand extends Command
{
    /**
     * @phpstan-var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $plainRefreshToken;
    
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
