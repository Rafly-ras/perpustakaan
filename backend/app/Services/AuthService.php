<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\MasterIdentity;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function register(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $roleName = strtolower($data['role']);
            $identityNumber = match ($roleName) {
                'mahasiswa' => $data['nim'] ?? null,
                'dosen' => $data['nidn'] ?? null,
                default => null,
            };

            if (! $identityNumber) {
                throw ValidationException::withMessages([
                    'identity_number' => ['Nomor Induk Mahasiswa (NIM) atau NIDN/NIP wajib diisi.'],
                ]);
            }

            // 1. Verify against master_identities
            $masterIdentity = MasterIdentity::where('identity_number', $identityNumber)
                ->where('role_type', $roleName)
                ->where('status', 'active')
                ->first();

            if (! $masterIdentity) {
                throw ValidationException::withMessages([
                    'identity_number' => ["NIM/NIDN ('{$identityNumber}') tidak terdaftar dalam data master perguruan tinggi atau berstatus tidak aktif. Silakan hubungi Administrator."],
                ]);
            }

            // 2. Verify identity isn't already claimed by an active user
            $existingUser = User::where('identity_number', $identityNumber)
                ->orWhere('nim', $identityNumber)
                ->orWhere('nidn', $identityNumber)
                ->orWhere('master_identity_id', $masterIdentity->id)
                ->first();

            if ($existingUser) {
                throw ValidationException::withMessages([
                    'identity_number' => ["NIM/NIDN ('{$identityNumber}') sudah memiliki akun terdaftar dalam sistem. Silakan login ke portal."],
                ]);
            }

            // 3. Assign initial coin balance based on FRD rules
            $coinBalance = match ($roleName) {
                'mahasiswa' => 5,
                'dosen' => 10,
                default => 0,
            };

            $roleModel = Role::where('name', $roleName)->first();

            $user = $this->userRepository->create([
                'master_identity_id' => $masterIdentity->id,
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
                'role' => $roleName,
                'role_id' => $roleModel?->id,
                'nim' => $roleName === 'mahasiswa' ? $identityNumber : null,
                'nidn' => $roleName === 'dosen' ? $identityNumber : null,
                'identity_number' => $identityNumber,
                'coin_balance' => $coinBalance,
                'status' => 'active',
                'is_active' => true,
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user->load('roleModel.permissions', 'masterIdentity'),
                'token' => $token,
            ];
        });
    }

    public function login(string $login, string $password): array
    {
        $user = $this->userRepository->findByIdentity($login);

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Kredensial email/NIM/NIDN atau password yang Anda masukkan salah.'],
            ]);
        }

        if (! $user->is_active || $user->status !== 'active') {
            throw ValidationException::withMessages([
                'login' => ['Akun Anda telah dinonaktifkan oleh Administrator. Silakan hubungi meja sirkulasi.'],
            ]);
        }

        // Record last_login_at timestamp
        $user->update(['last_login_at' => now()]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user->load('roleModel.permissions', 'masterIdentity'),
            'token' => $token,
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }

    public function me(User $user): User
    {
        return $user->load('roleModel.permissions', 'masterIdentity');
    }
}
