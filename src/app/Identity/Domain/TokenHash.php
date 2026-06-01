<?php declare(strict_types=1);

namespace App\Identity\Domain;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Shared\Domain\Primitive;

/**
 * @phpstan-extends Primitive<string>
 */
#[ORM\Embeddable]
final class TokenHash extends Primitive
{
    /**
     * @phpstan-var int
     */
    private const int MAX_LENGTH = 64;

    /**
     * @phpstan-var string
     */
    private const string REGEX = '/^[a-zA-Z0-9\-._]+$/';

    /**
     * @phpstan-var string
     */
    #[ORM\Column(name: 'token', type: Types::STRING, length: 64, unique: true)]
    private string $tokenHash {
        set (string $value) => $this->tokenHash = trim(string: $value);
    }

    /**
     * @phpstan-param string $tokenHash
     * @throws \DomainException
     */
    public function __construct(string $tokenHash)
    {
        self::ensureValidTokenHash(value: $tokenHash);
        $this->tokenHash = $tokenHash;
    }

    /**
     * @phpstan-param string $value
     * @throws \DomainException
     */
    private static function ensureValidTokenHash(string $value): void
    {
        if ($value === '') {
            throw new \DomainException(
                message: 'Token hash cannot be empty.'
            );
        }

        if (mb_strlen(string: $value) > self::MAX_LENGTH) {
            throw new \DomainException(
                message: 'Token hash cannot be longer than 64 characters.'
            );
        }

        if (preg_match(pattern: self::REGEX, subject: $value) === 0) {
            throw new \DomainException(
                message: 'Token hash contains invalid characters.'
            );
        }
    }

    /**
     * @phpstan-param string $value
     * @phpstan-return self
     */
    public static function of(string $value): self
    {
        return new self(tokenHash: $value);
    }

    /**
     * @phpstan-param string $plainToken
     * @phpstan-return self
     */
    public static function fromPlainToken(string $plainToken): self
    {
        return new self(tokenHash: hash(algo: 'sha256', data: $plainToken));
    }

    /**
     * @phpstan-param string $plainToken
     * @phpstan-return bool
     */
    public function matchesPlainToken(string $plainToken): bool
    {
        return $this->tokenHash === hash(algo: 'sha256', data: $plainToken);
    }

    /**
     * @phpstan-return string
     */
    public function value(): string
    {
        return $this->tokenHash;
    }

    /**
     * @phpstan-return string
     */
    public function __toString(): string
    {
        return $this->value();
    }
}
