<?php declare(strict_types=1);

namespace App\Identity\Application\Email\Verify;

enum VerifyEmailError: string
{
    /**
     * Invalid verification hash provided.
     */
    case InvalidHash = 'identity.email.verify.invalid_hash';
    
    /**
     * Email has already been verified.
     */
    case EmailVerified = 'identity.email.verify.already_completed';
    
    /**
     * Email verification failed due to an error.
     */
    case Failed = 'identity.email.verify.failed';
    
    /**
     * An unknown error occurred during email verification.
     */
    case Unknown = 'identity.email.verify.unknown';
    
    /**
     * @phpstan-return string
     */
    public function message(): string
    {
        $message = __(key: $this->value);
        return is_string(value: $message) ? $message : '';
    }
}
