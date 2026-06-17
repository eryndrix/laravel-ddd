<?php declare(strict_types=1);

namespace App\Identity\Infrastructure\Dispatching;

use Illuminate\Support\ServiceProvider;
use App\Identity\Application\Auth\Register\RegisterCommand;
use App\Identity\Application\Auth\Register\RegisterProcess;
use App\Identity\Application\Auth\Login\LoginCommand;
use App\Identity\Application\Auth\Login\LoginProcess;
use App\Identity\Application\Auth\RefreshToken\RefreshTokenCommand;
use App\Identity\Application\Auth\RefreshToken\RefreshTokenProcess;
use App\Identity\Application\Password\Forgot\ForgotPasswordCommand;
use App\Identity\Application\Password\Forgot\ForgotPasswordProcess;
use App\Identity\Application\Password\Reset\ResetPasswordCommand;
use App\Identity\Application\Password\Reset\ResetPasswordProcess;
use App\Identity\Application\Password\Update\UpdatePasswordCommand;
use App\Identity\Application\Password\Update\UpdatePasswordProcess;
use App\Identity\Application\Auth\Logout\LogoutCommand;
use App\Identity\Application\Auth\Logout\LogoutProcess;
use App\Identity\Application\Email\Update\UpdateEmailCommand;
use App\Identity\Application\Email\Update\UpdateEmailProcess;
// use App\Identity\Application\Profile\Update\UpdateProfileCommand;
// use App\Identity\Application\Profile\Update\UpdateProfileProcess;
// use App\Identity\Application\Profile\Delete\DeleteProfileCommand;
// use App\Identity\Application\Profile\Delete\DeleteProfileProcess;
use App\Shared\Domain\Bus\CommandBusInterface;

final class IdentityCommandDispatcher extends ServiceProvider
{
    /**
     * @phpstan-var array<class-string, class-string>
     */
    private array $auth = [
        RegisterCommand::class => RegisterProcess::class,
        LoginCommand::class => LoginProcess::class,
        RefreshTokenCommand::class => RefreshTokenProcess::class,
        LogoutCommand::class => LogoutProcess::class
    ];
    
    /**
     * @phpstan-var array<class-string, class-string>
     */
    private array $email = [
        UpdateEmailCommand::class => UpdateEmailProcess::class,
    ];
    
    /**
     * @phpstan-var array<class-string, class-string>
     */
    private array $password = [
        ForgotPasswordCommand::class => ForgotPasswordProcess::class,
        ResetPasswordCommand::class => ResetPasswordProcess::class,
        UpdatePasswordCommand::class => UpdatePasswordProcess::class,
    ];
    
    /**
     * @phpstan-var array<class-string, class-string>
     */
    private array $profile = [
        // UpdateProfileCommand::class => UpdateProfileProcess::class,
        // DeleteProfileCommand::class => DeleteProfileProcess::class
    ];

    /**
     * @phpstan-param CommandBusInterface<object, object> $commandBus
     * @phpstan-return void
     */
    public function boot(CommandBusInterface $commandBus): void
    {
        $commandBus->register(map: [
            ...$this->auth,
            ...$this->email,
            ...$this->password,
            ...$this->profile
        ]);
    }
}
