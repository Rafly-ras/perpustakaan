<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($userId)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'string', Rule::in(['super_admin', 'admin', 'mahasiswa', 'dosen'])],
            'nim' => ['nullable', 'string', 'max:50', Rule::unique('users', 'nim')->ignore($userId)],
            'nidn' => ['nullable', 'string', 'max:50', Rule::unique('users', 'nidn')->ignore($userId)],
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])],
            'coin_balance' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
