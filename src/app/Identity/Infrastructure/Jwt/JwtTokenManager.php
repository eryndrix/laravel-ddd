<?php declare(strict_types=1);

namespace App\Identity\Infrastructure\Jwt;

use Tymon\JWTAuth\Token as JwtToken;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Identity\Domain\TokenHash;
use App\Identity\Domain\Access\Jwt\JwtTokenManagerInterface;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWT;

final class JwtTokenManager implements JwtTokenManagerInterface
{
    /**
     * @phpstan-param JWT $jwt
     */
    public function __construct(
        private readonly JWT $jwt
    ) {}

    /**
     * @phpstan-param Authenticatable $user
     * @phpstan-return string
     * 
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function issueToken(Authenticatable $user): string
    {
        if (!$user instanceof JWTSubject) {
            throw new \InvalidArgumentException(
                message: 'User must implement JWTSubject interface.'
            );
        }

        $identifier = $user->getJWTIdentifier();
        
        if ($identifier === null || $identifier === '') {
            throw new \InvalidArgumentException(
                message: 'Valid non-empty user ID required.'
            );
        }

        return $this->jwt->fromUser(user: $user);
    }

    /**
     * @phpstan-param TokenHash $token
     * @phpstan-return bool
     * 
     * @throws \InvalidArgumentException
     */
    public function isTokenValid(
        #[\SensitiveParameter] TokenHash $token): bool
    {
        try {
            $jwtToken = new JwtToken(value: $token->value());
            $this->jwt->setToken(token: $jwtToken);

            $check = $this->jwt->check();
            
            if ($check === false) {
                return false;
            }

            $payload = $this->jwt->getPayload();
            
            if ($payload === null) {
                return false;
            }

            $exp = $payload->get(claim: 'exp');
            $iat = $payload->get(claim: 'iat');

            if (!is_int(value: $exp) || !is_int(value: $iat)) {
                return false;
            }

            $ttlSeconds = $exp - $iat;
            return $ttlSeconds <= 86400;
        }

        catch (\Throwable $e) {
            throw new \InvalidArgumentException(
                message: 'Token validation failed',
                code: $e->getCode(),
                previous: $e
            );
        }
    }

    /**
     * @phpstan-return void
     * @throws \RuntimeException
     */
    public function invalidateToken(): void
    {
        try {
            $token = $this->jwt->getToken();

            if ($token === null) {
                throw new \RuntimeException(
                    message: 'No active token found to invalidate.'
                );
            }

            $payload = $this->jwt->getPayload();

            if ($payload === null) {
                throw new \RuntimeException(
                    message: 'Unable to get token payload.'
                );
            }

            $exp = $payload->get(claim: 'exp');
            $iat = $payload->get(claim: 'iat');

            if (!is_int($exp) || !is_int($iat)) {
                throw new \RuntimeException(
                    message: 'Invalid token payload claims.'
                );
            }

            $ttlSeconds = $exp - $iat;

            if ($ttlSeconds > 86400) {
                throw new \RuntimeException(
                    message: 'Cannot invalidate refresh token.'
                );
            }

            $this->jwt->invalidate(
                forceForever: (bool) config(
                    key: 'jwt.blacklist_enabled',
                    default: true
                )
            );
        }

        catch (JWTException $e) {
            throw new \RuntimeException(
                message: 'Unable to invalidate token.',
                code: $e->getCode(),
                previous: $e
            );
        }

        catch (\Throwable $e) {
            throw new \RuntimeException(
                message: 'Token invalidation failed.',
                code: $e->getCode(),
                previous: $e
            );
        }
    }

    /**
     * @phpstan-param Authenticatable $user
     * @phpstan-param int $ttl
     * 
     * @phpstan-return string
     * 
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function issueRefreshJwt(
        Authenticatable $user, int $ttl): string 
    {
        if (!$user instanceof JWTSubject) {
            throw new \InvalidArgumentException(
                message: 'User must implement JWTSubject interface.'
            );
        }

        $identifier = $user->getJWTIdentifier();
        
        if ($identifier === null || $identifier === '') {
            throw new \InvalidArgumentException(
                message: 'Valid non-empty user ID required.'
            );
        }

        if ($ttl <= 0) {
            throw new \InvalidArgumentException(
                message: 'TTL must be a positive integer.'
            );
        }

        $this->jwt->factory()->setTTL(ttl: $ttl);
        return $this->jwt->fromUser(user: $user);
    }
}
