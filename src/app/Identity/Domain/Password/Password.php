<?php declare(strict_types=1);

namespace App\Identity\Domain\Password;

use Doctrine\ORM\Mapping as ORM;
use Illuminate\Support\Facades\Hash;

#[ORM\Embeddable]
final class Password extends HashPassword
{
    /**
     * @phpstan-var int
     */
    private const int MIN_LENGTH = 8;

    /**
     * @phpstan-var int
     */
    private const int MAX_LENGTH = 25;

    /**
     * @phpstan-param string $password
     * @phpstan-return bool
     */
    public static function isValidPassword(string $password): bool
    {
        $length = mb_strlen(
            string: $password,
            encoding: 'UTF-8'
        );
        
        return $length >= self::MIN_LENGTH
            && $length <= self::MAX_LENGTH;
    }

    /**
     * @phpstan-param string $value
     * @phpstan-return self
     * 
     * @throws \DomainException
     */
    public static function fromPlain(string $value): self
    {
        if (!self::isValidPassword(password: $value)) {
            throw new \DomainException(
                message: 'Password must be 8-25 chars.'
            );
        }

        $hash = Hash::make(value: $value);
        return new self(hash: $hash);
    }

    /**
     * @phpstan-param string $password
     * @phpstan-return bool
     */
    public function verify(string $password): bool
    {
        return Hash::check(
            value: $password,
            hashedValue: $this->hash
        );
    }

    /**
     * @phpstan-return bool
     */
    public function needsRehash(): bool
    {
        return Hash::needsRehash(
            hashedValue: $this->hash
        );
    }
}
