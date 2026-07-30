<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\DataTransferObjects\Auth\LoginData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginUserAction
{
    public function execute(LoginData $data): array
    {
        // Support login by Email or NIM/NIDN
        $user = User::with('role.permissions')
            ->where('email', $data->login)
            ->orWhere('identity_number', $data->login)
            ->first();

        if (! $user || ! Hash::check($data->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Kredensial email/NIM/NIDN atau password salah.'],
            ]);
        }

        // Revoke previous tokens if desired, create new Bearer token
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
