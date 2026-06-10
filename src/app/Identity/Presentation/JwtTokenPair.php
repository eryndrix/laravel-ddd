<?php declare(strict_types=1);

namespace App\Identity\Presentation;

final readonly class JwtTokenPair
{
    /**
     * @phpstan-param string $accessToken
     * @phpstan-param string $accessToken
     * @phpstan-param int $expiresIn
     * @phpstan-param string $tokenType
     */
    public function __construct(
        public private(set) string $accessToken,
        public private(set) string $refreshToken,
        public private(set) int $expiresIn,
        public private(set) string $tokenType = 'Bearer'
    ) {}

    /**
     * @phpstan-param array{
     *     access_token: string,
     *     ttl: int,
     *     refresh_token: string,
     *     refresh_ttl: \DateTimeImmutable
     * } $data
     * 
     * @phpstan-return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            accessToken: $data['access_token'],
            refreshToken: $data['refresh_token'],
            expiresIn: $data['ttl']
        );
    }

    /**
     * @phpstan-return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
            'expires_in' => $this->expiresIn,
            'token_type' => $this->tokenType
        ];
    }
}
