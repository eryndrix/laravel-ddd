<?php declare(strict_types=1);

namespace App\Identity\Domain\Changing;

use App\Identity\Domain\Token;
use App\Identity\Domain\Abilities;

final class TokenChanger
{
    /**
     * @phpstan-var bool
     */
    public private(set) bool $isDirty = false;

    /**
     * @phpstan-param Token $token
     */
    public function __construct(
        private Token $token
    ) {}

    /**
     * @phpstan-param string $name
     * @phpstan-return self
     * 
     * @throws \DomainException
     */
    public function name(string $name): self
    {
        if ($name === '') {
            throw new \DomainException(message: 'Name cannot be empty.');
        }

        if ($this->token->name === $name) {
            return $this;
        }

        $this->token->changeName(name: $name);
        $this->isDirty = true;

        return $this;
    }

    /**
     * @phpstan-param Abilities|null $abilities
     * @phpstan-return self
     */
    public function abilities(?Abilities $abilities): self
    {
        if ($abilities === null
            && $this->token->abilities === null
        ) {
            return $this;
        }

        if ($abilities !== null
            && $this->token->abilities !== null
        ) {
            if ($abilities->value()
                === $this->token->abilities->value()
            ) {
                return $this;
            }
        }

        $this->token->changeAbilities(abilities: $abilities);
        $this->isDirty = true;

        return $this;
    }

    /**
     * @phpstan-param \DateTimeImmutable|null $lastUsedAt
     * @phpstan-return self
     */
    public function lastUsedAt(
        ?\DateTimeImmutable $lastUsedAt): self
    {
        if ($this->token->lastUsedAt === $lastUsedAt) {
            return $this;
        }

        $this->token->changeLastUsedAt(lastUsedAt: $lastUsedAt);
        $this->isDirty = true;

        return $this;
    }

    /**
     * @phpstan-param \DateTimeImmutable|null $expiresAt
     * @phpstan-return self
     */
    public function expiresAt(
        ?\DateTimeImmutable $expiresAt): self
    {
        if ($this->token->expiresAt === $expiresAt) {
            return $this;
        }

        $this->token->changeExpiresAt(expiresAt: $expiresAt);
        $this->isDirty = true;

        return $this;
    }
}
