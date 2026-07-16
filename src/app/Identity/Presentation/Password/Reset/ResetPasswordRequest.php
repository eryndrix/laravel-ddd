<?php declare(strict_types=1);

namespace App\Identity\Presentation\Request\Password;

use App\Shared\Presentation\Request;
use Illuminate\Validation\Rules\Password;

final class ResetPasswordRequest extends Request
{
    /**
     * @phpstan-return array<string,
     *     \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string
     * >
     */
    public function rules(): array
    {
        $pwdRules = Password::min(size: 8)->letters()->numbers()->symbols();

        return [
            'email' => [
                'bail', 'required', 'email:rfc,strict,spoof,dns', 'max:244', 'exists:users,email'
            ],
            'password' => ['bail', 'required', 'string', $pwdRules, 'confirmed'],
            'token' => ['bail', 'required', 'string']
        ];
    }
}
