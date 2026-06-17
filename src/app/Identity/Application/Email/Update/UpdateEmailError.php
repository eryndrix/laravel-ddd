<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Update;

enum UpdateEmailError: string
{
    /**
     * Invalid email format provided.
     */
    case InvalidEmailFormat = 'identity.email.update.invalid_format';
    
    /**
     * Email is the same as current email.
     */
    case EmailSameAsCurrent = 'identity.email.update.same_as_current';
    
    /**
     * Unknown error during email update.
     */
    case Unknown = 'identity.email.update.failed';
    
    /**
     * @phpstan-return string
     */
    public function message(): string
    {
        $message = __(key: $this->value);
        return is_string(value: $message) ? $message : '';
    }
}
