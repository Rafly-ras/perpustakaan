<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\MasterIdentity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface MasterIdentityRepositoryInterface
{
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function findById(int $id): ?MasterIdentity;
    public function findByIdentityNumber(string $identityNumber): ?MasterIdentity;
    public function create(array $data): MasterIdentity;
    public function update(MasterIdentity $masterIdentity, array $data): MasterIdentity;
    public function delete(MasterIdentity $masterIdentity): bool;
}
