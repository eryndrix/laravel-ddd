<?php declare(strict_types=1);

namespace App\Shared\Domain\Id;

/**
 * @phpstan-extends UniqueId<TokenId>
 */
final class TokenId extends UniqueId
{
    /**
     * @phpstan-use IdFactory<TokenId>
     */
    use IdFactory;
}
