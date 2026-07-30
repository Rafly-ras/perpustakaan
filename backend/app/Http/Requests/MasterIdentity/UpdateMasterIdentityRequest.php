<?php

declare(strict_types=1);

namespace App\Http\Requests\MasterIdentity;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMasterIdentityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('master_identity');

        return [
            'identity_number' => ['required', 'string', 'max:50', Rule::unique('master_identities', 'identity_number')->ignore($id)],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role_type' => ['required', 'string', Rule::in(['mahasiswa', 'dosen'])],
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])],
        ];
    }
}
