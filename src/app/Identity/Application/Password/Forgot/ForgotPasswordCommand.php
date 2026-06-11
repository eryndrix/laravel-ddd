<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Forgot;

use App\Shared\Application\Command\Command;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\Casting\BooleanCast;
use WendellAdriel\ValidatedDTO\Attributes\Cast;

final class ForgotPasswordCommand extends Command
{
    /**
     * @phpstan-var string
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $email;

    /**
     * @phpstan-var bool|null
     */
    #[Cast(type: BooleanCast::class, param: null)]
    public ?bool $emailExists;

    /**
     * @phpstan-return array<string, mixed>
     */
    protected function defaults(): array
    {
        return ['emailExists' => false];
    }
}
