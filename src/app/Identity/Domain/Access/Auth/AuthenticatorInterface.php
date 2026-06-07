<?php declare(strict_types=1);

namespace App\Identity\Domain\Access\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

interface AuthenticatorInterface
{
    /**
     * @phpstan-param array<string, mixed> $credentials
     * @phpstan-return Authenticatable|null
     */
	public function authenticate(array $credentials): ?Authenticatable;
}
