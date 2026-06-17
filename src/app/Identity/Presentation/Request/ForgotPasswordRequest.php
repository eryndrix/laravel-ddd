<?php declare(strict_types=1);

namespace App\Identity\Presentation\Request;

use App\Shared\Presentation\Request;

final class ForgotPasswordRequest extends Request
{
    /**
     * @phpstan-return array<string,
     *     \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string
     * >
     */
    public function rules(): array
    {
        return [
            'email' => [
                'bail', 'required', 'email:rfc,strict,spoof', 'max:244', 'exists:users,email' 
            ],
        ];
    }
}

