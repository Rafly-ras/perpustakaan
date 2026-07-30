<?php

declare(strict_types=1);

namespace App\Http\Requests\MasterIdentity;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMasterIdentityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'identity_number' => ['required', 'string', 'max:50', 'unique:master_identities,identity_number'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role_type' => ['required', 'string', Rule::in(['mahasiswa', 'dosen'])],
            'status' => ['nullable', 'string', Rule::in(['active', 'inactive'])],
        ];
    }

    public function messages(): array
    {
        return [
            'identity_number.required' => 'Nomor identitas (NIM/NIDN) wajib diisi.',
            'identity_number.unique' => 'Nomor identitas (NIM/NIDN) ini sudah ada di master data.',
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'role_type.required' => 'Tipe peran (mahasiswa/dosen) wajib dipilih.',
            'role_type.in' => 'Tipe peran hanya boleh mahasiswa atau dosen.',
        ];
    }
}
