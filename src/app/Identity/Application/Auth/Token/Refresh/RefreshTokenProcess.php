<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Token\Refresh;

use App\Shared\Application\Process;
use App\Identity\Application\Auth\Token\Refresh\Handler\ResolveRefreshTokenHandler;
use App\Identity\Application\Auth\Token\Refresh\Handler\CheckTokenExpirationHandler;
use App\Identity\Application\Auth\Token\Refresh\Handler\CheckTokenAbilitiesHandler;
use App\Identity\Application\Auth\Token\Refresh\Handler\DetectTokenReuseHandler;
use App\Identity\Application\Auth\Token\Refresh\Handler\LoadUserHandler;
use App\Identity\Application\Auth\Token\Refresh\Handler\RevokeOldTokenHandler;
use App\Identity\Application\Auth\Token\Refresh\Handler\EmitNewTokensHandler;
use App\Identity\Application\Auth\Token\Refresh\Handler\PersistNewRefreshTokenHandler;
use App\Identity\Application\Auth\Token\TokenData;

/**
 * @phpstan-extends Process<RefreshTokenCommand, RefreshTokenCommand>
 */
final class RefreshTokenProcess extends Process
{
    /**
     * @phpstan-var list<class-string>
     */
    protected array $handlers = [
        ResolveRefreshTokenHandler::class,
        CheckTokenExpirationHandler::class,
        CheckTokenAbilitiesHandler::class,
        DetectTokenReuseHandler::class,
        LoadUserHandler::class,
        RevokeOldTokenHandler::class,
        EmitNewTokensHandler::class,
        PersistNewRefreshTokenHandler::class,
    ];

    /**
     * @phpstan-param RefreshTokenCommand $command
     * @phpstan-return TokenData
     */
    public function execute(RefreshTokenCommand $command): TokenData
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
