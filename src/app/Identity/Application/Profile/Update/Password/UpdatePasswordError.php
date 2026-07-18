<?php declare(strict_types=1);

namespace App\Identity\Application\Password\Update;

enum UpdatePasswordError: string
{
    /**
     * Password confirmation does not match.
     */
    case Mismatch = 'identity.password.update.mismatch';

    /**
     * Password format is invalid.
     */
    case InvalidPwdFormat = 'identity.password.update.invalid_pwd_format';

    /**
     * Password update failed due to an error.
     */
    case Failed = 'identity.password.update.failed';

    /**
     * Unknown error during password update.
     */
    case Unknown = 'identity.password.update.unknown';
    
    /**
     * @phpstan-return string
     */
    public function message(): string
    {
        $message = __(key: $this->value);
        return is_string(value: $message) ? $message : '';
    }
}
