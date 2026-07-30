<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', Rule::in(['super_admin', 'admin', 'mahasiswa', 'dosen'])],
            'nim' => ['nullable', 'string', 'max:50', 'unique:users,nim'],
            'nidn' => ['nullable', 'string', 'max:50', 'unique:users,nidn'],
            'status' => ['nullable', 'string', Rule::in(['active', 'inactive'])],
            'coin_balance' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
