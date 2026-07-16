<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Login;

use App\Shared\Application\Process;
use App\Identity\Application\Auth\Login\Handler\AuthenticateUserHandler;
use App\Identity\Application\Auth\Login\Handler\RevokeOldRefreshTokensHandler;
use App\Identity\Application\Auth\Login\Handler\IssueJwtTokensHandler;
use App\Identity\Application\Auth\Login\Handler\PersistRefreshTokenHandler;
use App\Identity\Application\Auth\Login\Handler\UpdateLastLoginAtHandler;
use App\Identity\Application\Auth\Token\TokenData;

/**
 * @phpstan-extends Process<LoginCommand, LoginCommand>
 */
final class LoginProcess extends Process
{
    /**
     * @phpstan-var list<class-string>
     */
    protected array $handlers = [
        AuthenticateUserHandler::class,
        RevokeOldRefreshTokensHandler::class,
        IssueJwtTokensHandler::class,
        PersistRefreshTokenHandler::class,
        UpdateLastLoginAtHandler::class,
    ];

    /**
     * @phpstan-param LoginCommand $command
     * @phpstan-return TokenData
     */
    public function execute(LoginCommand $command): TokenData
    {
        $result = $this->run(payload: $command);

        /**
         * @phpstan-var array{
         *     access_token: string,
         *     ttl: int,
         *     refresh_token: string,
         *     refresh_ttl: \DateTimeImmutable
         * } $jwtTokenPair
         */
        $jwtTokenPair = $result->jwtTokenPair;

        return TokenData::fromArray(data: $jwtTokenPair);
    }
}
