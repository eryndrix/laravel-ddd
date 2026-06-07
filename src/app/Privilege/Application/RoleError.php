<?php declare(strict_types=1);

namespace App\Privilege\Application;

enum RoleError: string
{
    /**
     * Role not found error.
     */
    case NotFound = 'role.not_found';

    /**
     * Per page value out of range error.
     */
    case PerPageOutOfRange = 'pagination.per_page_out_of_range';

    /**
     * Unexpected error.
     */
    case UnexpectedError = 'common.unexpected_error';
    
    /**
     * @phpstan-return string
     */
    public function message(): string
    {
        $message = __(key: $this->value);
        return is_string(value: $message) ? $message : '';
    }
}
