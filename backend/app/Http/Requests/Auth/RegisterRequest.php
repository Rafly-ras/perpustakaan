<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::in(['mahasiswa', 'dosen'])],
            'nim' => ['nullable', 'required_if:role,mahasiswa', 'string', 'max:50', 'unique:users,nim'],
            'nidn' => ['nullable', 'required_if:role,dosen', 'string', 'max:50', 'unique:users,nidn'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email telah terdaftar dalam sistem.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.unique' => 'Nomor WhatsApp telah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Peran akun wajib dipilih.',
            'role.in' => 'Registrasi mandiri hanya untuk Mahasiswa dan Dosen.',
            'nim.required_if' => 'NIM wajib diisi untuk registrasi Mahasiswa.',
            'nim.unique' => 'NIM telah terdaftar.',
            'nidn.required_if' => 'NIDN/NIP wajib diisi untuk registrasi Dosen.',
            'nidn.unique' => 'NIDN/NIP telah terdaftar.',
        ];
    }
}
