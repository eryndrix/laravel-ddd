<?php declare(strict_types=1);

namespace App\Identity\Domain\Password;

use Doctrine\ORM\Mapping as ORM;
use App\Shared\Domain\Primitive;

/**
 * @phpstan-extends Primitive<string>
 */
#[ORM\MappedSuperclass]
class HashPassword extends Primitive
{
    /**
     * @phpstan-var string
     */
    #[ORM\Column(name: 'password', type: 'string', length: 60)]
    protected string $hash;

    /**
     * @phpstan-param string $hash
     * @throws \DomainException
     */
    public function __construct(string $hash)
    {
        if (!$this->isValidHash(hash: $hash)) {
            throw new \DomainException(
                message: 'Password hash must be 60 characters.'
            );
        }

        $this->hash = $hash;
    }

    /**
     * @phpstan-param string $hash
     * @phpstan-return bool
     */
    protected function isValidHash(string $hash): bool
    {
        return mb_strlen(string: $hash, encoding: 'UTF-8') === 60;
    }

    /**
     * @phpstan-return string
     */
    public function value(): string
    {
        return $this->hash;
    }

    /**
     * @phpstan-return string
     */
    public function __toString(): string
    {
        return $this->value();
    }
}
