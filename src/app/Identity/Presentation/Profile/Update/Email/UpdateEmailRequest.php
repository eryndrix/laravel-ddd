<?php declare(strict_types=1);

namespace App\Identity\Presentation\Profile\Update\Email;

use App\Shared\Presentation\Request;

final class UpdateEmailRequest extends Request
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
                'bail',
                'required',
                'email:rfc,strict,spoof,dns',
                'max:244',
                'unique:users,email'
            ]
        ];
    }
}
