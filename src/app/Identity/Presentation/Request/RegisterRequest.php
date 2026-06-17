<?php declare(strict_types=1);

namespace App\Identity\Presentation\Request;

use Illuminate\Validation\Rules\Password;
use App\Shared\Presentation\Request;

final class RegisterRequest extends Request
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
            'name' => ['bail', 'required', 'string', 'min:2', 'max:46'],
            'email' => [
                'bail', 'required', 'email:rfc,strict,spoof,dns', 'max:244', 'unique:users,email'
            ],
            'password' => ['bail', 'required', 'string', $pwdRules, 'confirmed'],
        ];
    }
}
