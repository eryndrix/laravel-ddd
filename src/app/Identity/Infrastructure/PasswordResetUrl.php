<?php declare(strict_types=1);

namespace App\Identity\Infrastructure;

use Illuminate\Support\ServiceProvider;
use App\Identity\Infrastructure\Auth\UserAdapter;
use Illuminate\Auth\Notifications\ResetPassword;

final class PasswordResetUrl extends ServiceProvider
{
    /**
     * @phpstan-return void
     * @throws \InvalidArgumentException
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(
            callback: function (mixed $user, string $token): string {
                if (!$user instanceof UserAdapter) {
                    throw new \InvalidArgumentException(
                        message: 'User must be an instance of UserAdapter.'
                    );
                }
                
                $params = "token={$token}&email={$user->getEmailForPasswordReset()}";

                /** @phpstan-var non-falsy-string $appUrl */
                $appUrl = config(key: 'app.url');
                return $appUrl . "/password/reset?{$params}";
            }
        );
    }
}
