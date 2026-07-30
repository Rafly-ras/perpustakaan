<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\DataTransferObjects\Auth\RegisterData;
use App\Models\MasterIdentity;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegisterUserAction
{
    public function execute(RegisterData $data): array
    {
        return DB::transaction(function () use ($data) {
            // 1. Verify or Auto-Create Master Identity (Allows registration for any input NIM/NIDN)
            $master = MasterIdentity::firstOrCreate(
                [
                    'identity_number' => $data->identity_number,
                ],
                [
                    'full_name' => $data->name,
                    'identity_type' => ucfirst(strtolower($data->role_type)),
                    'is_registered' => false,
                ]
            );

            if ($master->is_registered && User::where('identity_number', $data->identity_number)->exists()) {
                throw ValidationException::withMessages([
                    'identity_number' => ['NIM/NIDN ini sudah terikat pada akun pengguna aktif.'],
                ]);
            }

            // 2. Resolve or Auto-Create Role (Mahasiswa / Dosen)
            $roleName = strtolower($data->role_type);
            $role = Role::firstOrCreate(
                ['name' => $roleName],
                ['display_name' => ucfirst($roleName)]
            );

            // 3. Determine Initial Coin Quota (Mahasiswa = 5, Dosen = 10)
            $initialCoins = match ($roleName) {
                'dosen' => (int) config('app.default_dosen_coins', env('DEFAULT_DOSEN_COINS', 10)),
                default => (int) config('app.default_mahasiswa_coins', env('DEFAULT_MAHASISWA_COINS', 5)),
            };

            // 4. Create User Record
            $user = User::create([
                'identity_number' => $data->identity_number,
                'name' => $data->name,
                'email' => $data->email,
                'phone' => $data->phone,
                'password' => Hash::make($data->password),
                'role_id' => $role->id,
                'coin_balance' => $initialCoins,
            ]);

            // 5. Mark Master Identity as Registered
            $master->update(['is_registered' => true]);

            // 6. Create Sanctum Bearer Token
            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user->load('role.permissions'),
                'token' => $token,
            ];
        });
    }
}
