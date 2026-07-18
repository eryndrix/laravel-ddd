<?php declare(strict_types=1);

namespace App\Identity\Infrastructure\Dispatching;

use Illuminate\Support\ServiceProvider;
use App\Identity\Application\Auth\Register\RegisterCommand;
use App\Identity\Application\Auth\Register\RegisterUseCase;
use App\Identity\Application\Auth\Login\LoginCommand;
use App\Identity\Application\Auth\Login\LoginUseCase;
use App\Identity\Application\Auth\Token\Refresh\RefreshTokenCommand;
use App\Identity\Application\Auth\Token\Refresh\RefreshTokenUseCase;
use App\Identity\Application\Password\Forgot\ForgotPasswordCommand;
use App\Identity\Application\Password\Forgot\ForgotPasswordUseCase;
use App\Identity\Application\Password\Reset\ResetPasswordCommand;
use App\Identity\Application\Password\Reset\ResetPasswordUseCase;
use App\Identity\Application\Password\Update\UpdatePasswordCommand;
use App\Identity\Application\Password\Update\UpdatePasswordProcess;
use App\Identity\Application\Auth\Logout\LogoutCommand;
use App\Identity\Application\Auth\Logout\LogoutUseCase;
use App\Identity\Application\Profile\Update\Email\UpdateEmailCommand;
use App\Identity\Application\Profile\Update\Email\UpdateEmailUseCase;
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
        RegisterCommand::class => RegisterUseCase::class,
        LoginCommand::class => LoginUseCase::class,
        RefreshTokenCommand::class => RefreshTokenUseCase::class,
        LogoutCommand::class => LogoutUseCase::class
    ];
    
    /**
     * @phpstan-var array<class-string, class-string>
     */
    private array $email = [
        UpdateEmailCommand::class => UpdateEmailUseCase::class,
    ];
    
    /**
     * @phpstan-var array<class-string, class-string>
     */
    private array $password = [
        ForgotPasswordCommand::class => ForgotPasswordUseCase::class,
        ResetPasswordCommand::class => ResetPasswordUseCase::class,
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
