<?php declare(strict_types=1);

namespace App\Identity\Presentation\Request;

use Illuminate\Validation\Rules\Password;
use App\Shared\Presentation\Request;

final class LoginRequest extends Request
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
                'bail', 'required', 'email:rfc,strict,spoof', 'max:254', 'exists:users,email'
            ],
            'password' => ['bail', 'required', 'string', 'min:8', 'max:28'],
            'remember_me' => ['bail', 'sometimes', 'boolean'],
        ];
    }

    /**
     * @phpstan-return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge(input: [
            'remember_me' => $this->boolean(
                key: 'remember_me',
                default: false
            ),
        ]);
    }
}
