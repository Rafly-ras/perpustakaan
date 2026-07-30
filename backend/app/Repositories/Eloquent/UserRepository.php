<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        return User::with(['roleModel.permissions'])->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::with(['roleModel.permissions'])->where('email', $email)->first();
    }

    public function findByIdentity(string $identifier): ?User
    {
        return User::with(['roleModel.permissions'])
            ->where('email', $identifier)
            ->orWhere('nim', $identifier)
            ->orWhere('nidn', $identifier)
            ->orWhere('identity_number', $identifier)
            ->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh();
    }
}
