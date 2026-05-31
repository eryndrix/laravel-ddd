<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Id;

use App\Shared\Domain\Id\TokenId;

/**
 * @phpstan-extends UniqueIdType<TokenId>
 */
final class TokenIdType extends UniqueIdType
{
    /**
     * @phpstan-var class-string<TokenId>
     */
    public const string NAME = TokenId::class;

    /**
     * @phpstan-return class-string<TokenId>
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @phpstan-return class-string<TokenId>
     */
    public function getValueObjectClass(): string
    {
        return TokenId::class;
    }
}
