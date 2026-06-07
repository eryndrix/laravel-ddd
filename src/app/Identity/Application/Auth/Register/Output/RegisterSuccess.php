<?php declare(strict_types=1);

namespace App\Identity\Application\Auth\Register\Output;

final class RegisterSuccess
{
    /**
     * @phpstan-param string $message
     */
    public function __construct(
        private string $message = 'auth.registration.success'
    ) {}

    /**
     * @phpstan-return string
     */
    public function message(): string
    {
        $message = __(key: $this->message);
        return is_string(value: $message) ? $message : '';
    }
}
