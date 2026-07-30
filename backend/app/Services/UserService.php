<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = User::with(['roleModel.permissions', 'masterIdentity']);

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                  ->orWhere('email', 'ILIKE', "%{$search}%")
                  ->orWhere('nim', 'ILIKE', "%{$search}%")
                  ->orWhere('nidn', 'ILIKE', "%{$search}%")
                  ->orWhere('identity_number', 'ILIKE', "%{$search}%");
            });
        }

        if (! empty($filters['role'])) {
            $query->where('role', strtolower($filters['role']));
        }

        if (! empty($filters['status'])) {
            $query->where('status', strtolower($filters['status']));
        }

        return $query->latest('id')->paginate($perPage);
    }

    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $roleName = strtolower($data['role']);
            $roleModel = Role::where('name', $roleName)->first();

            $coinBalance = $data['coin_balance'] ?? match ($roleName) {
                'mahasiswa' => 5,
                'dosen' => 10,
                default => 0,
            };

            $identityNumber = $data['nim'] ?? $data['nidn'] ?? null;

            return $this->userRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
                'role' => $roleName,
                'role_id' => $roleModel?->id,
                'nim' => $data['nim'] ?? null,
                'nidn' => $data['nidn'] ?? null,
                'identity_number' => $identityNumber,
                'coin_balance' => (int) $coinBalance,
                'status' => $data['status'] ?? 'active',
                'is_active' => ($data['status'] ?? 'active') === 'active',
            ]);
        });
    }

    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            if (isset($data['role'])) {
                $roleName = strtolower($data['role']);
                $roleModel = Role::where('name', $roleName)->first();
                $data['role_id'] = $roleModel?->id;
            }

            if (! empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            if (isset($data['status'])) {
                $data['is_active'] = $data['status'] === 'active';
            }

            return $this->userRepository->update($user, $data);
        });
    }

    public function delete(User $user): bool
    {
        if ($user->role === 'super_admin') {
            throw ValidationException::withMessages([
                'user' => ['Akun Super Administrator utama tidak dapat dihapus dari sistem.'],
            ]);
        }

        return (bool) $user->delete();
    }

    public function resetPassword(User $user, string $newPassword): User
    {
        return $this->userRepository->update($user, [
            'password' => Hash::make($newPassword),
        ]);
    }

    public function activate(User $user): User
    {
        return $this->userRepository->update($user, [
            'status' => 'active',
            'is_active' => true,
        ]);
    }

    public function deactivate(User $user): User
    {
        if ($user->role === 'super_admin') {
            throw ValidationException::withMessages([
                'user' => ['Akun Super Administrator tidak dapat dinonaktifkan.'],
            ]);
        }

        return $this->userRepository->update($user, [
            'status' => 'inactive',
            'is_active' => false,
        ]);
    }
}
