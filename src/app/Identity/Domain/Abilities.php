<?php declare(strict_types=1);

namespace App\Identity\Domain;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Shared\Domain\Primitive;

/**
 * @phpstan-extends Primitive<array<string>>
 */
#[ORM\Embeddable]
final class Abilities extends Primitive
{
    /**
     * @phpstan-var int
     */
    private const int MAX_ABILITIES_COUNT = 50;

    /**
     * @phpstan-var array<string>
     */
    #[ORM\Column(name: 'abilities', type: Types::JSON, nullable: true)]
    private array $abilities;
    
    /**
     * @phpstan-param array<string> $abilities
     * @throws \DomainException
     */
    public function __construct(array $abilities)
    {
        $normalized = self::normalizeAbilities(
            abilities: $abilities
        );

        if (count(value: $normalized) > self::MAX_ABILITIES_COUNT) {
            throw new \DomainException(
                message: 'Too many abilities. Maximum is 50.'
            );
        }

        $this->abilities = $normalized;
    }
    
    /**
     * @phpstan-param array<string> $abilities
     * @phpstan-return array<string>
     */
    private static function normalizeAbilities(array $abilities): array
    {
        $nonEmpty = array_filter(
            array: $abilities,
            callback: fn (string $ability): bool => $ability !== ''
        );

        $unique = array_unique(array: $nonEmpty);
        return array_values(array: $unique);
    }

    /**
     * @phpstan-param string $json
     * @phpstan-return self
     * 
     * @throws \DomainException
     */
    public static function fromJson(string $json): self
    {
        $decoded = json_decode(json: $json, associative: true);

        if (!is_array(value: $decoded)) {
            throw new \DomainException(
                message: 'Invalid JSON: must be an array.'
            );
        }

        foreach ($decoded as $item) {
            if (!is_string(value: $item)) {
                throw new \DomainException(
                    message: 'Invalid JSON: all abilities must be strings.'
                );
            }
        }

        /** @var array<string> $decoded */
        return new self(abilities: $decoded);
    }

    /**
     * @phpstan-param array<string> $value
     * @phpstan-return self
     */
    public static function fromArray(array $value): self
    {
        return new self(abilities: $value);
    }

    /**
     * @phpstan-param string $ability
     * @phpstan-return bool
     */
    public function has(string $ability): bool
    {
        return in_array(
            needle: $ability,
            haystack: $this->abilities,
            strict: true
        );
    }

    /**
     * @phpstan-param self $other
     * @phpstan-return self
     */
    public function merge(self $other): self
    {
        $abilities = array_unique(array: [
            ...$this->abilities,
            ...$other->abilities
        ]);

        return new self(abilities: $abilities);
    }

    /**
     * @phpstan-return array<string>
     */
    public function value(): array
    {
        return $this->abilities;
    }

    /**
     * @phpstan-return string
     * @throws \DomainException
     */
    public function asJson(): string
    {
        $encoded = json_encode(
            value: $this->value(),
            flags: JSON_UNESCAPED_SLASHES
        );

        if ($encoded === false) {
            throw new \DomainException(
                message: 'JSON encoding failed'
            );
        }

        return $encoded;
    }

    /**
     * @phpstan-return string
     */
    public function __toString(): string
    {
        return $this->asJson();
    }
}
