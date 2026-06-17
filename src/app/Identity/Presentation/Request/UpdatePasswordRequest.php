<?php declare(strict_types=1);

namespace App\Identity\Presentation\Request;

use App\Shared\Presentation\Request;
use Illuminate\Validation\Rules\Password;

final class UpdatePasswordRequest extends Request
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
            'password' => ['bail', 'required', 'string', $pwdRules, 'confirmed'],
        ];
    }
}
