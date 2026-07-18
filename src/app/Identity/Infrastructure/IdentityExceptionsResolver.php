<?php declare(strict_types=1);

namespace App\Identity\Infrastructure;

use Illuminate\Support\ServiceProvider;
use App\Identity\Application\Auth\Login\Exception\LoginExceptionResolver;
use App\Identity\Application\Auth\Register\Exception\RegisterExceptionResolver;
use App\Identity\Application\Auth\Logout\Exception\LogoutExceptionResolver;
use App\Identity\Application\Auth\Check\UserExceptionResolver;
use App\Identity\Application\Auth\Token\Refresh\Exception\RefreshTokenExceptionResolver;
use App\Identity\Application\Email\Update\Exception\UpdateEmailExceptionResolver;
use App\Identity\Application\Email\Verify\Exception\VerifyEmailExceptionResolver;
use App\Identity\Application\Password\Forgot\Exception\ForgotPasswordExceptionResolver;
use App\Identity\Application\Password\Reset\Exception\ResetPasswordExceptionResolver;

final class IdentityExceptionsResolver extends ServiceProvider
{
    /**
     * @phpstan-var list<class-string>
     */
    private array $auth = [
        LoginExceptionResolver::class,
        RegisterExceptionResolver::class,
        LogoutExceptionResolver::class,
        RefreshTokenExceptionResolver::class,
        UserExceptionResolver::class,
    ];

    /**
     * @phpstan-var list<class-string>
     */
    private array $email = [
        UpdateEmailExceptionResolver::class,
        VerifyEmailExceptionResolver::class
    ];

    /**
     * @phpstan-var list<class-string>
     */
    private array $password = [
        ForgotPasswordExceptionResolver::class,
        ResetPasswordExceptionResolver::class
    ];

    /**
     * @phpstan-return void
     */
    public function register(): void
    {
        foreach ([
            ...$this->auth,
            ...$this->email,
            ...$this->password
        ] as $resolver) {
            $this->app->singleton(abstract: $resolver);
        }
    }
}
