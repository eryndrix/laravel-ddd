<?php declare(strict_types=1);

namespace App\Identity\Domain;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Shared\Domain\Primitive;

/**
 * @phpstan-extends Primitive<string>
 */
#[ORM\Embeddable]
final class Avatar extends Primitive
{
    /**
     * @phpstan-var string
     */
    #[ORM\Column(name: 'avatar', type: Types::STRING, length: 255, nullable: true)]
    private string $avatar;

    /**
     * @phpstan-param string $avatar
     * @throws \DomainException
     */
    public function __construct(string $avatar)
    {
        $avatar = trim(string: $avatar);

        if ($avatar === '') {
            throw new \DomainException(
                message: 'Avatar path cannot be empty.'
            );
        }

        if (mb_strlen(string: $avatar) > 255) {
            throw new \DomainException(
                message: 'Avatar path exceeds 255 characters.'
            );
        }

        $this->avatar = $avatar;
    }

    /**
     * @phpstan-param string $value
     * @phpstan-return self
     */
    public static function of(string $value): self
    {
        return new self(avatar: $value);
    }

    /**
     * @phpstan-return string
     */
    public function value(): string
    {
        return $this->avatar;
    }

    /**
     * @phpstan-return string
     */
    public function __toString(): string
    {
        return $this->value();
    }
}
