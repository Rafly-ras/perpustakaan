<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\MasterIdentity;
use App\Repositories\Contracts\MasterIdentityRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MasterIdentityRepository implements MasterIdentityRepositoryInterface
{
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = MasterIdentity::query();

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('identity_number', 'ILIKE', "%{$search}%")
                  ->orWhere('full_name', 'ILIKE', "%{$search}%")
                  ->orWhere('email', 'ILIKE', "%{$search}%");
            });
        }

        if (! empty($filters['role_type'])) {
            $query->where('role_type', strtolower($filters['role_type']));
        }

        if (! empty($filters['status'])) {
            $query->where('status', strtolower($filters['status']));
        }

        return $query->latest('id')->paginate($perPage);
    }

    public function findById(int $id): ?MasterIdentity
    {
        return MasterIdentity::find($id);
    }

    public function findByIdentityNumber(string $identityNumber): ?MasterIdentity
    {
        return MasterIdentity::where('identity_number', $identityNumber)->first();
    }

    public function create(array $data): MasterIdentity
    {
        return MasterIdentity::create($data);
    }

    public function update(MasterIdentity $masterIdentity, array $data): MasterIdentity
    {
        $masterIdentity->update($data);
        return $masterIdentity->fresh();
    }

    public function delete(MasterIdentity $masterIdentity): bool
    {
        return (bool) $masterIdentity->delete();
    }
}
