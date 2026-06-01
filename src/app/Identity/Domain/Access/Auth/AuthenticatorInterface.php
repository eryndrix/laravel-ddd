<?php declare(strict_types=1);

namespace App\Identity\Domain\Access\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

interface AuthenticatorInterface
{
    /**
     * @phpstan-param CredentialData $data
     * @phpstan-return Authenticatable
     */
	public function authenticate(CredentialData $data): Authenticatable;
}
