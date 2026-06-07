<?php declare(strict_types=1);

namespace App\Identity\Domain\Changing;

/**
 * @phpstan-template TEntity of \App\Identity\Domain\Token
 */
trait TokenStateChange
{
    /**
     * @phpstan-param string $ability
     * @phpstan-return bool
     */
    public function can(string $ability): bool
    {
        return $this->abilities !== null
            && $this->abilities->has(ability: $ability);
    }

    /**
     * @phpstan-return void
     */
    public function markAsUsed(): void
    {
        if ($this->lastUsedAt?->getTimestamp()
            === (new \DateTimeImmutable())->getTimestamp()
        ) {
            return;
        }

        $this->lastUsedAt = new \DateTimeImmutable();
    }

    /**
     * @phpstan-return void
     */
    public function revoke(): void
    {
        if ($this->isRevoked()) {
            return;
        }

        $oldExpiresAt = $this->expiresAt;
        $this->expiresAt = new \DateTimeImmutable();

        $occurredAt = $this->expiresAt;
    }

    /**
     * @phpstan-return bool
     */
    public function isRevoked(): bool
    {
        return $this->expiresAt !== null
            && $this->expiresAt <= new \DateTimeImmutable();
    }
}
