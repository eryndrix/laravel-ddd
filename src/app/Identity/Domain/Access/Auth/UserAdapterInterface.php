<?php declare(strict_types=1);

namespace App\Identity\Domain\Access\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Identity\Domain\User;

interface UserAdapterInterface extends
    Authenticatable,
    MustVerifyEmail,
    CanResetPassword
{
    /**
     * @phpstan-return User
     */
	public function unwrap(): User;
}
